<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'nameField',
        'location',
        'desc',
        'type',
        'price',
        'imgUrl',
        'openClock',
        'closeClock',
        'totalField',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    // Tambahan metode dan relasi sesuai kebutuhan Anda
    public function user()
{
    return $this->belongsTo(User::class);
}

}
