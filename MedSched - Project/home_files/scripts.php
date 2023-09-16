<script>
  function loadSearchResults(event) {
    event.preventDefault();

    const formData = new FormData(event.target);
    const searchParams = new URLSearchParams();

    for (const pair of formData) {
      searchParams.append(pair[0], pair[1]);
    }

    fetch('../search.php?' + searchParams.toString())
      .then(response => response.text())
      .then(html => {
        const searchResultsDiv = document.getElementById('search-results');
        searchResultsDiv.innerHTML = html;
      });
  }

  function handleLogout(event) {
    event.preventDefault();
    window.location.href = 'logout.php';
  }

  // Add event listener for search form
  document.querySelector('form').addEventListener('submit', loadSearchResults);

  // Add event listener for logout form
  document.querySelector('#logout form').addEventListener('submit', handleLogout);
</script>
