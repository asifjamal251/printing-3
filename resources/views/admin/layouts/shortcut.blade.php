<script>
document.addEventListener('keydown', function (event) {
    // Check if the Command (Meta) and Option (Alt) keys are pressed and the 'P' key is pressed
    if (event.metaKey && event.altKey && event.key === 'c') {
        event.preventDefault(); // Prevent the default behavior (if any)
        window.location.href = '/product'; // Redirect to the /product route
    }
});

</script>