$(document).ready(function(){
  // Find replacementElements with data-replacement.
  $("[data-replacement]").each((replacementIndex, replacementElement) => {
    // Get replacementElement's data properties.
    let replacementValue = $(replacementElement).data("replacement");
    if (!replacementValue) {
      console.log("Loading error: no replacement value.");
      return;
    }
    let pathToRoot = $(replacementElement).data("path-to-root");
    if (typeof pathToRoot === "undefined") {
      pathToRoot = "";
    }
    // Compute path to replacement file.
    let pathToFile = pathToRoot + replacementValue;
    // Load the file.
    $(replacementElement).load(pathToFile, (response, status, xhr) => {
      // Did it break?
      if (status == "error") {
        console.log("Something went wrong loading the file " + replacementValue + ".");
        return;
      }
      // OK
      $.ajax({
        url: pathToRoot + 'library/login_status.php',
        method: 'GET',
        data: { path_to_root: pathToRoot },
        success: function(response) {
            $("#login-logout").html(response);
        }
      });
      // Exit if no path to root.
      if (!pathToRoot) {
        console.log("No path to root given for " + replacementValue + ", links not adjusted.");
      }
      // Find the <a> tags.
      $(replacementElement).find("a").each((aIndex, aElement) => {
        // Exit if data-no-update exists.
        if (typeof $(aElement).data('no-update') !== "undefined") {
          return;
        }
        let hrefValue = $(aElement).attr("href");
        // Exit if not found.
        if (!hrefValue) {
          return;
        }
        hrefValue = hrefValue.toLowerCase().trim();
        // Exit if value is #.
        if (hrefValue === "#") {
          return;
        }
        // Exit if URL is absolute.
        if (hrefValue.indexOf("http://") === 0 || hrefValue.indexOf("https://") === 0) {
          return;
        }
        // Change the href.
        $(aElement).attr("href", pathToRoot + hrefValue);
      });
    });
  });
});


