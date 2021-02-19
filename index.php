<!DOCTYPE html>
<html>
<head>
  <script src="js/fabric2.js"></script>
  <script src="js/face-api.js"></script>
  <script src="js/faceDetectionControls.js"></script>
  <script src="js/imageSelectionControls.js"></script>
  <link rel="stylesheet" href="styles.css">
  <link rel="stylesheet" href="materialize.css">
  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script src="js/materialize.js"></script>
</head>
<body>
  <div class="center-content page-container">
  	
    <div style="position: relative">
      <p id="debug"><span id="xtrack"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="ytrack"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="scale"></span></p>
      <canvas id="temp"></canvas>
      <canvas id="overlay"></canvas>
      <canvas id="canvas" style="border:1px solid"></canvas>

      <div id="right_tool">
        <p class="range-field">
          <input type="range" id="test5" min="50" max="150" step="10" />
        </p>
        <br>
        <div class="switch">
          <label>
            Eyebrown
            <input id="eye_show" type="checkbox" onchange="eyeshow(this);">
            <span class="lever"></span>
          </label>
        </div>
        <div class="switch">
          <label>
            Lips
            <input id="lip_show" type="checkbox">
            <span class="lever"></span>
          </label>
        </div>
        <div class="switch">
          <label>
            Grid
            <input id="grid_show" type="checkbox" onchange="gridshow(this);">
            <span class="lever"></span>
          </label>
        </div>
      </div>

      <div id="left_tool">
        <a class="btn-floating btn-medium waves-effect waves-light blue"><img class="material-icons" src="resource/eye_icon.png" /></a>
        <a class="btn-floating btn-medium waves-effect waves-light blue"><img class="material-icons" src="resource/lips_icon.png" /></a>

        <div class="file_input_div">
          <div class="file_input">
            <label class="image_input_button mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-js-ripple-effect mdl-button--colored">
              <a class="btn-floating btn-medium waves-effect waves-light teal"><i class="material-icons">image</i></a>
              <input id="queryImgUploadInput" type="file" class="none" onchange="loadImageFromUpload()" accept=".jpg, .jpeg, .png">
            </label>
          </div>
        </div>

        <a id="color1" class="class-color btn-floating btn-medium waves-effect waves-light grey" onclick="setColor(this)"></a>
        <a id="color2" class="class-color btn-floating btn-medium waves-effect waves-light grey" onclick="setColor(this)"></a>
        <a id="color3" class="class-color btn-floating btn-medium waves-effect waves-light grey" onclick="setColor(this)"></a>
      </div>

      <div id="bottom_tool">
        <div class="parent">
          <div class="child">
            <p id="eye_resource"></p>
          </div>
        </div>
      </div>

      <img id="inputImg" src="" style="height:0px;display: none;" />
      <img id="tempImg" src="" style="height:0px;display: none;" />
    </div>
  </div>

  <script src="js/config.js"></script>
  <script src="js/events.js"></script>
  <script src="js/process.js"></script>
</body>
</html>