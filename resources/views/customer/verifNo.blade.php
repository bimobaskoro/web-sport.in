<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/css/insertCode.css">
  <title>Bootstrap Site</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</head>
<body>
    <div class="verification-form">
        <p>Masukkan kode verifikasi:</p>
        <form id="verification-form" action="{{ route('post-code') }}" method="post">
            @csrf
            <input type="text" class="verification-input" id="digit1" name="verification_code[]" maxlength="1" oninput="moveToNext(this)" required>
            <input type="text" class="verification-input" id="digit2" name="verification_code[]" maxlength="1" oninput="moveToNext(this)" required>
            <input type="text" class="verification-input" id="digit3" name="verification_code[]" maxlength="1" oninput="moveToNext(this)" required>
            <input type="text" class="verification-input" id="digit4" name="verification_code[]" maxlength="1" oninput="moveToNext(this)" required>
            <input type="text" class="verification-input" id="digit5" name="verification_code[]" maxlength="1" oninput="moveToNext(this)" required>            
            <br>
            <button type="submit" class="submit-button">Submit</button>
            <p id="resend-message" style="display: none;">Kirim ulang kode dalam <span id="countdown">60</span> detik</p>
        </form>        
    </div>
    <script>
        var countdown = 60;
        var resendTimer;
        showResendMessage();

        function moveToNext(currentInput) {
            var maxLength = parseInt(currentInput.maxLength);
            var currentInputId = currentInput.id;

            if (currentInput.value.length === maxLength) {
                var nextInputId = parseInt(currentInputId[currentInputId.length - 1]) + 1;
                var nextInput = document.getElementById("digit" + nextInputId);

                if (nextInput) {
                    nextInput.focus();
                } else {
                    document.getElementById("verification-form").submit();
                }
            }
        }

        function showResendMessage() {
            document.getElementById("resend-message").style.display = "block";
            startResendTimer();
        }

        function startResendTimer() {
            resendTimer = setInterval(function () {
                countdown--;
                document.getElementById("countdown").innerText = countdown;

                if (countdown <= 0) {
                    clearInterval(resendTimer);
                    document.getElementById("resend-message").innerHTML = "<span id='send-again' style='cursor: pointer; color: blue; text-decoration: underline;' onclick='sendCodeAgain()'>Kirim ulang kode</span>";
                }
            }, 1000);
        }

        function sendCodeAgain() {
            // Add logic to resend the code or update UI as needed
            countdown = 60; // Reset the countdown
            document.getElementById("resend-message").innerHTML = "Kirim ulang kode dalam <span id='countdown'>60</span> detik";
            startResendTimer(); // Start the timer again
        }
    </script>
</body>
</html>
