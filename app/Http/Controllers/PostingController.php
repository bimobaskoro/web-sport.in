<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posting; // Pastikan mengimpor model Posting
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Auth;

class PostingController extends Controller
{
    public function index(Request $request)
    {
        $postings = Posting::latest()->get();
        $filter = $this->applyFilters($request);

        return view('customer.findPlayer', compact('postings','filter'));
    }

    public function post(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'required|string|max:500',
            'maxPlayer'=>'required|integer',
             'title'=>'required|string'
        ]);

        $user = auth()->user();

        $posting = new Posting([
            'name' => $request->input('name'),
            'desc' => $request->input('desc'),
            'maxPlayer' => $request->input('maxPlayer'),
            'title'=>$request->input('title'),
            'user_id' => $user->id,

        ]);

        // Simpan posting
        $posting->save();

        return redirect()->route('findPlayer');
    }

    
    private function applyFilters(Request $request)
    {
        $postings = Posting::query();

        $postType = $request->input('post_type');

        if ($postType == 'latest') {
            $postings->latest();
        } elseif ($postType == 'oldest') {
            $postings->oldest();
        } elseif ($postType == 'yourPosts') {
            $user = auth()->user();
            $postings->where('user_id', $user->id);
        } elseif ($postType == 'all') {
            // Show all posts
        }

        return $postings->get();
    }

    public function edit($postId): View
{
    $posting = Posting::findOrFail($postId);

    // Check if the authenticated user is the owner of the post
    if (auth()->user()->id !== $posting->user_id) {
        abort(403, 'Unauthorized action.');
    }

    return view('edit-post', compact('posting'));
}
public function update(Request $request, $postId): RedirectResponse
{
    $posting = Posting::findOrFail($postId);

    // Check if the authenticated user is the owner of the post
    if (auth()->user()->id !== $posting->user_id) {
        abort(403, 'Unauthorized action.');
    }

    // Validate the request data
    $request->validate([
        'title' => 'required|string',
        'maxPlayer' => 'required|integer',
        'desc' => 'required|string|max:500',
    ]);

    // Update the post
    $posting->update([
        'title' => $request->input('title'),
        'maxPlayer' => $request->input('maxPlayer'),
        'desc' => $request->input('desc'),
    ]);

    return redirect()->route('findPlayer')->with('success', 'Post updated successfully!');
}


public function delete($postId)
{
    $posting = Posting::find($postId);

    if (!$posting) {
        return Redirect::route('findPlayer')->with('error', 'Post not found');
    }

    // Check if the user is authorized to delete the post
    if ($posting->user_id !== auth()->id()) {
        return Redirect::route('findPlayer')->with('error', 'Unauthorized to delete this post');
    }

    $posting->delete();

    return Redirect::route('findPlayer')->with('success', 'Post deleted successfully');
}
}
