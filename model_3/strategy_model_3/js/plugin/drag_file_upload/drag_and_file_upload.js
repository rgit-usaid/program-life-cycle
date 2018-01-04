(function(window) {
    function triggerCallback(e, callback) {
      if(!callback || typeof callback !== 'function') {
        return;
      }
      var files;
	  if(e.dataTransfer) {
        files = e.dataTransfer.files;
      } else if(e.target) {
        files = e.target.files;
      }
      callback.call(null, files);
    }
    function makeDroppable(ele, callback) {
      var input = document.createElement('input');
      input.setAttribute('type', 'file');
	  input.setAttribute('name', 'file');
      input.setAttribute('multiple', true);
      input.style.display = 'none';
      input.addEventListener('change', function(e) {
        triggerCallback(e, callback);
      });
      ele.appendChild(input);
      
      ele.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        ele.classList.add('dragover');
      });

      ele.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        ele.classList.remove('dragover');
      });

      ele.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        ele.classList.remove('dragover');
        triggerCallback(e, callback);
      });
      
      ele.addEventListener('click', function() {
        input.value = null;
        input.click();
      });
    }
    window.makeDroppable = makeDroppable;
  })(this);
  (function(window) {
    makeDroppable(window.document.querySelector('.demo-droppable'), function(files) {
	  var output = document.querySelector('.demo-droppable');
      output.innerHTML = '';
      for(var i=0; i<files.length; i++) {
        output.innerHTML += files[i].name;
	  }
	 //output.innerHTML = output.innerHTML.substr(0,output.innerHTML.length-2);
    });
  })(this);
  
  var element = document.querySelector('.demo-droppable');
function callback(files) {
  var formData = new FormData();
  formData.append("files", files);

  $.ajax({
    url: 'upload.php',
    method: 'post',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      alert('Files uploaded successfully.');
    }
  });
}
makeDroppable(element, callback);