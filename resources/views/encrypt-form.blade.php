<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Encrypt Data</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.0.0/crypto-js.min.js"></script>
</head>
<body>
    <form id="myForm" action="{{ route('process') }}" method="POST">
        @csrf
        <input type="text" id="data" name="data" placeholder="Enter data">
        <button type="submit">Submit</button>
    </form>

    <script>
        document.getElementById('myForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var data = document.getElementById('data').value;
            var key = CryptoJS.enc.Utf8.parse('secret key 123');
            var iv = CryptoJS.lib.WordArray.random(16); // Generate a random IV

            var encrypted = CryptoJS.AES.encrypt(data, key, { iv: iv });
            var encryptedData = encrypted.ciphertext.toString(CryptoJS.enc.Base64);
            var ivBase64 = iv.toString(CryptoJS.enc.Base64);

            var formData = new FormData();
            formData.append('data', encryptedData);
            formData.append('iv', ivBase64);

            fetch('{{ route('process') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            }).then(response => response.text())
              .then(data => console.log(data))
              .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
