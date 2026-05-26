
        // JavaScript to handle date picker change
        document.getElementById("datePicker").addEventListener("change", function() {
            let selectedDate = this.value;
            let formData = new FormData();
            formData.append('date', selectedDate);

            fetch('retrive-diagnose.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                // Update only the prescription part of the page
                document.querySelector('.profile-container2').innerHTML = data;
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
   