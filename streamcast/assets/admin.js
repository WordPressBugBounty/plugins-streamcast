(function ($) {
  $(document).ready(function () {
    // Copy Quick Embed Shortcode
    $(document).on("click", ".streamcast_shortcode_copy_btn", function (e) {
      e.preventDefault();
      const $btn = $(this);
      const text = $btn.data("shortcode") || $btn.data("clipboard-text");
      const $textSpan = $btn.find(".copy-text");
      const originalText = $textSpan.length ? $textSpan.text() : "";

      if (!text) return;

      const performFeedback = () => {
        $btn.addClass("copied");
        if ($textSpan.length) {
          $textSpan.text("Copied!");
          setTimeout(() => {
            $btn.removeClass("copied");
            $textSpan.text(originalText);
          }, 2000);
        } else {
          // Fallback if no span, maybe a tooltip or just a class
          setTimeout(() => {
            $btn.removeClass("copied");
          }, 2000);
        }
      };

      if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(performFeedback);
      } else {
        const tempInput = document.createElement("input");
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);
        performFeedback();
      }
    });
  });
})(jQuery);