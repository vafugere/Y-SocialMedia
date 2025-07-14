document.addEventListener('DOMContentLoaded', function () {
  const Font = Quill.import('formats/font'); // Capital Q!
  Font.whitelist = ['serif', 'monospace', 'comic', 'impact', 'brush'];
  Quill.register(Font, true);

  const quill = new Quill('#editor', {
    theme: 'snow',
    modules: { toolbar: '#toolbar' }
  });

  document.querySelector('#post_form').addEventListener('submit', function () {
    document.querySelector('#hiddenMessage').value = quill.root.innerHTML;
  });
});
