<!DOCTYPE html>
<html>
<head>
  <script src="fabric.js"></script>
  <script src="face-api.js"></script>
  
  <script src="js/faceDetectionControls.js"></script>
  <script src="js/imageSelectionControls.js"></script>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
</head>
<body>
  <div class="center-content page-container">

    <div style="position: relative" class="margin">
      <canvas id="overlay"></canvas>
      <canvas id="canvas" style="border:1px solid"></canvas>
    </div>

    <div class="row side-by-side">
      <!-- image_selection_control -->
      <div id="selectList"></div>
      <div class="row">
        <label for="imgUrlInput">Get image from URL:</label>
        <input id="imgUrlInput" type="text" class="bold">
      </div>
      <button
        class="waves-effect waves-light btn"
        onclick="loadImageFromUrl()"
      >
        Ok
      </button>
      <button
        class="waves-effect waves-light btn"
        onclick="applyFilter()"
      >
        Apply
      </button>

      <input id="queryImgUploadInput" type="file" class="waves-effect btn bold" onchange="loadImageFromUpload()" accept=".jpg, .jpeg, .png">
      <!-- image_selection_control -->
    </div>
    <img id="inputImg" src="" style="max-width: 800px;visibility: hidden;" />
  </body>

  <script>
    let withBoxes = true
    var kanvas = new fabric.Canvas('canvas');
    var scale = 1;
    function onChangeHideBoundingBoxes(e) {
      withBoxes = !$(e.target).prop('checked')
      updateResults()
    }

    async function updateResults() {
		if (!isFaceDetectionModelLoaded()) {
			return
		}

		const inputImgEl = $('#inputImg').get(0)
		const options = getFaceDetectorOptions()

		const results = await faceapi.detectAllFaces(inputImgEl, options).withFaceLandmarks()

		const canvas = $('#overlay').get(0);
		const cansult = $('#canvas').get(0);
		faceapi.matchDimensions(canvas, inputImgEl)
		//const resizedResults = faceapi.resizeResults(results, inputImgEl)
		var _w = canvas.width;
		var _h = canvas.height;
		kanvas.setHeight(_h);
		kanvas.setWidth(_w);
		scale = _w/inputImgEl.naturalWidth;
		var _img = null;
		var imgElement = document.getElementById('inputImg');
		var _img = new fabric.Image(imgElement, {
			left: 0,
			top: 0,
			selectable: false,

		}).scale(scale);
		kanvas.add(_img);
		setTimeout(function(){
          console.log(results[0].landmarks.positions);
          for (var i = 28; i < results[0].landmarks.positions.length; i++) {
          	var _x = results[0].landmarks.positions[i]._x;
          	var _y = results[0].landmarks.positions[i]._y;
            kanvas.add(new fabric.Circle({ radius: 2, fill: '#f0f', top: _y*scale, left: _x*scale }).set('hasControls', false));
          }
          kanvas.sendBackwards(_img);
          fabric.Image.fromURL('eye2.png', function(oImg) { 
            var eyescl = (results[0].landmarks.positions[21]._x - results[0].landmarks.positions[17]._x) / 80;
            oImg.left = results[0].landmarks.positions[17]._x + 5*scale ;
            oImg.top = results[0].landmarks.positions[17]._y - (oImg.height*eyescl) + 5*scale;
            oImg.scale(eyescl);
            oImg.clone(function(clone) {
                kanvas.add(clone.set({
                    left: results[0].landmarks.positions[22]._x + 5*scale,
                    top: results[0].landmarks.positions[22]._y - (oImg.height*eyescl) + 5*scale
                }));
            });
            oImg.set('flipX', true);
            kanvas.add(oImg);
          });

          kanvas.on('object:selected', function(o){
            var activeObj = o.target;
            activeObj.set({'borderColor':'#f00','cornerColor':'#fbb802'});
          });
        }, 500);
      //if (withBoxes) {
        //faceapi.draw.drawDetections(canvas, resizedResults)
      //}
      //faceapi.draw.drawFaceLandmarks(canvas, resizedResults)
    }

    function applyFilter(){
      var ctx=kanvas.contextContainer.canvas.getContext('2d');
      var imageData=ctx.getImageData(0,0,kanvas.width,kanvas.height);
      invertColors(imageData.data, kanvas.width);
      kanvas.contextContainer.putImageData(imageData, 0, 0);
    }

    var leng = 1000;
    var step = 4;
    var opa = 255;
    function invertColors(data, width) {
      for (var i = 0; i < width*step; i+= step) {
        data[i] = 255; // Invert Red
        data[i+1] = 0; // Invert Green
        data[i+2] = 0; // Invert Blue
        data[i+3] = opa;
      }
    }

    async function run() {
      // load face detection and face landmark models
      await changeFaceDetector(SSD_MOBILENETV1)
      await faceapi.loadFaceLandmarkModel('/')

      // start processing image
      //updateResults()
    }

    $(document).ready(function() {
      //initImageSelectionControls()
      //initFaceDetectionControls()
      run()
    })
  </script>
</body>
</html>