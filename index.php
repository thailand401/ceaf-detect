<!DOCTYPE html>
<html>
<head>
  <script src="fabric.js"></script>
  <script src="face-api.js"></script>
  <script src="js/commons.js"></script>
  <script src="js/faceDetectionControls.js"></script>
  <script src="js/imageSelectionControls.js"></script>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
</head>
<body>
  <div id="navbar"></div>
  <div class="center-content page-container">

    <div class="progress" id="loader">
      <div class="indeterminate"></div>
    </div>
    <div style="position: relative" class="margin">
      <img id="inputImg" src="" style="max-width: 800px;" />
      <canvas id="canvas" style="border:1px solid"></canvas>
      <canvas id="overlay" />
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
      <input id="queryImgUploadInput" type="file" class="waves-effect btn bold" onchange="loadImageFromUpload()" accept=".jpg, .jpeg, .png">
      <!-- image_selection_control -->
    </div>

    <div class="row side-by-side">

      <!-- face_detector_selection_control -->
      <div id="face_detector_selection_control" class="row input-field" style="margin-right: 20px;">
        <select id="selectFaceDetector">
          <option value="ssd_mobilenetv1">SSD Mobilenet V1</option>
          <option value="tiny_face_detector">Tiny Face Detector</option>
        </select>
        <label>Select Face Detector</label>
      </div>
      <!-- face_detector_selection_control -->

      <!-- check boxes -->
      <div class="row" style="width: 220px;">
        <input type="checkbox" id="hideBoundingBoxesCheckbox" onchange="onChangeHideBoundingBoxes(event)" />
        <label for="hideBoundingBoxesCheckbox">Hide Bounding Boxes</label>
      </div>
      <!-- check boxes -->

    </div>

    <!-- ssd_mobilenetv1_controls -->
    <span id="ssd_mobilenetv1_controls">
      <div class="row side-by-side">
        <div class="row">
          <label for="minConfidence">Min Confidence:</label>
          <input disabled value="0.5" id="minConfidence" type="text" class="bold">
        </div>
        <button
          class="waves-effect waves-light btn"
          onclick="onDecreaseMinConfidence()"
        >
          <i class="material-icons left">-</i>
        </button>
        <button
          class="waves-effect waves-light btn"
          onclick="onIncreaseMinConfidence()"
        >
          <i class="material-icons left">+</i>
        </button>
      </div>
    </span>
    <!-- ssd_mobilenetv1_controls -->

    <!-- tiny_face_detector_controls -->
    <span id="tiny_face_detector_controls">
      <div class="row side-by-side">
        <div class="row input-field" style="margin-right: 20px;">
          <select id="inputSize">
            <option value="" disabled selected>Input Size:</option>
            <option value="160">160 x 160</option>
            <option value="224">224 x 224</option>
            <option value="320">320 x 320</option>
            <option value="416">416 x 416</option>
            <option value="512">512 x 512</option>
            <option value="608">608 x 608</option>
          </select>
          <label>Input Size</label>
        </div>
        <div class="row">
          <label for="scoreThreshold">Score Threshold:</label>
          <input disabled value="0.5" id="scoreThreshold" type="text" class="bold">
        </div>
        <button
          class="waves-effect waves-light btn"
          onclick="onDecreaseScoreThreshold()"
        >
          <i class="material-icons left">-</i>
        </button>
        <button
          class="waves-effect waves-light btn"
          onclick="onIncreaseScoreThreshold()"
        >
          <i class="material-icons left">+</i>
        </button>
      </div>
    </span>
    <!-- tiny_face_detector_controls -->

  </body>

  <script>
    let withBoxes = true
    var kanvas = new fabric.Canvas('canvas');

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
		var scale = _w/inputImgEl.naturalWidth;
		var _img = null;
		var imgElement = document.getElementById('inputImg');
		var _img = new fabric.Image(imgElement, {
			left: 0,
			top: 0,
			selectable: false,

		}).scale(scale);
		kanvas.add(_img);
		setTimeout(function(){
          for (var i = 0; i < results[0].landmarks.positions.length; i++) {
          	var _x = results[0].landmarks.positions[i]._x;
          	var _y = results[0].landmarks.positions[i]._y;
            kanvas.add(new fabric.Circle({ radius: 2, fill: '#f0f', top: _y*scale, left: _x*scale }).set('hasControls', false));
          }
          kanvas.sendBackwards(_img);
        }, 500);
      //if (withBoxes) {
        //faceapi.draw.drawDetections(canvas, resizedResults)
      //}
      //faceapi.draw.drawFaceLandmarks(canvas, resizedResults)
    }

    async function run() {
      // load face detection and face landmark models
      await changeFaceDetector(SSD_MOBILENETV1)
      await faceapi.loadFaceLandmarkModel('/')

      // start processing image
      updateResults()
    }

    $(document).ready(function() {
      renderNavBar('#navbar', 'face_landmark_detection')
      initImageSelectionControls()
      initFaceDetectionControls()
      run()
    })
  </script>
</body>
</html>