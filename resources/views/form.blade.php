<!DOCTYPE html>
<html>
<head>
    <title>Bootstrap Form Example</title>
    <!-- Add Bootstrap CSS CDN link -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    <div class="container">
        <h2>Save Your Information</h2>
        <form id="userForm">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Add Bootstrap JS and jQuery CDN links (required for Bootstrap) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userForm = document.getElementById('userForm');

            userForm.addEventListener('submit', (e) => {
                e.preventDefault();

                // Get user data from the form
                const name = document.getElementById('name').value;
                const password = document.getElementById('password').value;
                const email = document.getElementById('email').value;

                // Store data in localStorage
                const userData = {
                    name: name,
                    password: password,
                    email: email
                };

                // Check if localStorage is available
                if (typeof (Storage) !== 'undefined') {
                    // Store data in localStorage
                    localStorage.setItem('userData', JSON.stringify(userData));

                    // Redirect to a success page or display a success message
                    alert('Data stored in localStorage. It will be synced with the server when online.');

                    if (navigator.onLine) {
                        // Send data to the server using AJAX
                        console.log("Sending data to the server");
                        $.ajax({
                            url: '{{ route("user.store") }}',
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify(userData),
                            success: function (response) {
                                console.log('Data stored in the database:', response);

                                // Remove data from localStorage after successful insertion
                                localStorage.removeItem('userData');
                                console.log('Data removed from localStorage.');
                            },
                            error: function (xhr, status, error) {
                                console.log('Error sending data to the server: ' + error);
                            }
                        });
                    }
                } else {
                    alert('localStorage is not supported in this browser.');
                }

                // Reset the form
                userForm.reset();
            });
        });

        window.addEventListener('online', () => {
            // When the system comes back online, trigger synchronization
            alert("System is back online.");

            // Check if there's data in localStorage
            const userData = localStorage.getItem('userData');

            if (userData && navigator.onLine) {
                // Send data to the server using AJAX
                console.log("Sending data to the server");
                $.ajax({
                    url: '{{ route("user.store") }}',
                    type: 'POST',
                    contentType: 'application/json',
                    data: userData,
                    success: function (response) {
                        console.log('Data stored in the database:', response);

                        // Remove data from localStorage after successful insertion
                        localStorage.removeItem('userData');
                        console.log('Data removed from localStorage.');
                    },
                    error: function (xhr, status, error) {
                        console.log('Error sending data to the server: ' + error);
                    }
                });
            }
        });
    </script>

</body>
</html>
