<!DOCTYPE html>
<html>
<head>
  <script src="fabric2.js"></script>
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
  	<p><span id="xtrack"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="ytrack"></span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="scale"></span></p>
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
    <img id="inputImg2" src="" style="max-width: 800px;" />
    <img id="inputImg" src="" style="max-width: 800px;visibility: hidden;" />
  </body>

  <script>
    let withBoxes = true
    var kanvas = new fabric.Canvas('canvas');
    var scale = 1;
    var scaleMatrix = 1;
    var boundeye = [
    	{x: -14, y: 15},
    	{x: -11.5, y: -7},
    	{x: 0, y: -12.8},
    	{x: 0, y: -12.8},
    	{x: 1.28, y: -12.8},
    	{x: 10.23, y: -8.95},
    	{x: 13, y: 13},
    ];
    var eyeClear = [];
    var eyeClear2 = [];
    var is_grid = false;
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
    if(inputImgEl.naturalWidth < 1023)
      scaleMatrix = inputImgEl.naturalWidth/1023;

    for (var i = 0; i < boundeye.length; i++) {
      boundeye[i].x *= scaleMatrix;
      boundeye[i].y *= scaleMatrix;
    }

		scale = _w/inputImgEl.naturalWidth;
		$("#scale").text(scale+'---'+scaleMatrix);
		var _img = null;
		var imgElement = document.getElementById('inputImg');
		var _img = new fabric.Image(imgElement, {
			left: 0,
			top: 0,
			selectable: false,

		}).scale(scale);
		kanvas.add(_img);
		setTimeout(function(){
          eyeClear = [], eyeClear2 = [];
          for (var i = 0; i < results[0].landmarks.positions.length; i++) {
          	var _x = results[0].landmarks.positions[i]._x;
          	var _y = results[0].landmarks.positions[i]._y;
          	
          	if(i>= 17 && i<=26){
          		_y += 7/(scale/scaleMatrix);
          	}
//-----------------------------------EYE DATA 2--------------------------
          	if(i == 22){
          		eyeClear2.push({x: _x+(-1*boundeye[6].x), y: _y+boundeye[6].y});
          	}

          	if(i >= 22 && i <= 26){
          		eyeClear2.push({x: _x, y: _y});
          		eyeClear2.push({x: _x+(-1*boundeye[6-(i-21)].x), y: _y+boundeye[6-(i-21)].y});
          		eyeClear2.push({x: _x+boundeye[6-(i-21)].x, y: _y+(-1*boundeye[6-(i-21)].y)});
          	}

          	if(i == 26){
          		eyeClear2.push({x: _x+(-1*boundeye[0].x), y: _y+boundeye[0].y});
          	}
//-----------------------------------EYE DATA 1--------------------------
			if(i == 17){
          		eyeClear.push({x: _x+boundeye[0].x, y: _y+boundeye[0].y});
          	}

          	if(i >= 17 && i <= 21){
          		eyeClear.push({x: _x, y: _y});
          		eyeClear.push({x: _x+boundeye[i-16].x, y: _y+boundeye[i-16].y});
          		eyeClear.push({x: _x+(-1*boundeye[i-16].x), y: _y+(-1*boundeye[i-16].y)});
          	}

          	if(i == 21){
          		eyeClear.push({x: _x+boundeye[6].x, y: _y+boundeye[6].y});
          	}
//-----------------------------------END DATA----------------------------
			if(i>26){
            	kanvas.add(new fabric.Circle({ radius: 2, fill: '#f0f', top: _y*scale, left: _x*scale }).set('hasControls', false));
        	}
          }

          	if(is_grid == true){
	          	kanvas.add(new fabric.Line([eyeClear[0].x*scale, eyeClear[0].y*scale, eyeClear[1].x*scale, eyeClear[1].y*scale], { 
	            	stroke: 'green' 
	        	}));
	        	kanvas.add(new fabric.Line([eyeClear2[0].x*scale, eyeClear2[0].y*scale, eyeClear2[1].x*scale, eyeClear2[1].y*scale], { 
	            	stroke: 'green' 
	        	}));
	        	for (var i = 1; i < eyeClear.length-1; i+=3) {
	        		j = i+3;
	        		j = (j>eyeClear.length-1)?eyeClear.length-1:j;
		        	kanvas.add(new fabric.Line([eyeClear[i].x*scale, eyeClear[i].y*scale, eyeClear[j].x*scale, eyeClear[j].y*scale], { 
		            	stroke: 'green' 
		        	}));
		        	kanvas.add(new fabric.Line([eyeClear2[i].x*scale, eyeClear2[i].y*scale, eyeClear2[j].x*scale, eyeClear2[j].y*scale], { 
		            	stroke: 'green' 
		        	}));
	        	}
	        	//------------------
	        	kanvas.add(new fabric.Line([eyeClear[0].x*scale, eyeClear[0].y*scale, eyeClear[2].x*scale, eyeClear[2].y*scale], { 
	            	stroke: 'green' 
	        	}));
	        	kanvas.add(new fabric.Line([eyeClear2[0].x*scale, eyeClear2[0].y*scale, eyeClear2[2].x*scale, eyeClear2[2].y*scale], { 
	            	stroke: 'green' 
	        	}));
	        	for (var i = 2; i < eyeClear.length-1; i+=3) {
	        		j = i+3;
	        		j = (j>eyeClear.length-1)?eyeClear.length-1:j;
		        	kanvas.add(new fabric.Line([eyeClear[i].x*scale, eyeClear[i].y*scale, eyeClear[j].x*scale, eyeClear[j].y*scale], { 
		            	stroke: 'green' 
		        	}));
		        	kanvas.add(new fabric.Line([eyeClear2[i].x*scale, eyeClear2[i].y*scale, eyeClear2[j].x*scale, eyeClear2[j].y*scale], { 
		            	stroke: 'green' 
		        	}));
	        	}
	        	//------------------
	        	kanvas.add(new fabric.Line([eyeClear[0].x*scale, eyeClear[0].y*scale, eyeClear[3].x*scale, eyeClear[3].y*scale], { 
	            	stroke: 'green' 
	        	}));
	        	kanvas.add(new fabric.Line([eyeClear2[0].x*scale, eyeClear2[0].y*scale, eyeClear2[3].x*scale, eyeClear2[3].y*scale], { 
	            	stroke: 'green' 
	        	}));
	        	for (var i = 3; i < eyeClear.length-1; i+=3) {
	        		j = i+3;
	        		j = (j>eyeClear.length-1)?eyeClear.length-1:j;
		        	kanvas.add(new fabric.Line([eyeClear[i].x*scale, eyeClear[i].y*scale, eyeClear[j].x*scale, eyeClear[j].y*scale], { 
		            	stroke: 'green' 
		        	}));
		        	kanvas.add(new fabric.Line([eyeClear2[i].x*scale, eyeClear2[i].y*scale, eyeClear2[j].x*scale, eyeClear2[j].y*scale], { 
		            	stroke: 'green' 
		        	}));
	        	}

	        	for (var i = 1; i < eyeClear.length-1; i+=3) {
	        		kanvas.add(new fabric.Line([eyeClear[i].x*scale, eyeClear[i].y*scale, eyeClear[i+1].x*scale, eyeClear[i+1].y*scale], { 
		            	stroke: 'green' 
		        	}));
		        	kanvas.add(new fabric.Line([eyeClear[i].x*scale, eyeClear[i].y*scale, eyeClear[i+2].x*scale, eyeClear[i+2].y*scale], { 
		            	stroke: 'green' 
		        	}));

		        	kanvas.add(new fabric.Line([eyeClear2[i].x*scale, eyeClear2[i].y*scale, eyeClear2[i+1].x*scale, eyeClear2[i+1].y*scale], { 
		            	stroke: 'green' 
		        	}));
		        	kanvas.add(new fabric.Line([eyeClear2[i].x*scale, eyeClear2[i].y*scale, eyeClear2[i+2].x*scale, eyeClear2[i+2].y*scale], { 
		            	stroke: 'green' 
		        	}));
	        	}
			}
			getListColor(eyeClear);
        	//-------------------
          	kanvas.sendBackwards(_img);
          	fabric.Image.fromURL('eye2.png', function(oImg) {
          	/*
            var eyescl = (results[0].landmarks.positions[21]._x - results[0].landmarks.positions[17]._x) / 80;
            oImg.left = results[0].landmarks.positions[17]._x*scale + 5*scale ;
            oImg.top = results[0].landmarks.positions[17]._y*scale - (oImg.height*eyescl) + 5*scale;
            oImg.scale(eyescl);
            oImg.clone(function(clone) {
                kanvas.add(clone.set({
                    left: results[0].landmarks.positions[22]._x*scale + 5*scale,
                    top: results[0].landmarks.positions[22]._y*scale - (oImg.height*eyescl) + 5*scale
                }));
            });
            oImg.set('flipX', true);
            kanvas.add(oImg);
            */
          });

          kanvas.on('mouse:move', function(o){
          	$("#xtrack").text(parseInt(o.pointer.x));
          	$("#ytrack").text(parseInt(o.pointer.y));
          })
          kanvas.on('mouse:down', function(o){
            var activeObj = o.target;
            activeObj.set({'borderColor':'#3498DB','cornerColor':'#1F618D'});
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
      var bs64 = kanvas.contextContainer.canvas.toDataURL();
      $('#inputImg2').attr('src', bs64);
    }

    var leng = 1000;
    var step = 4;
    var opa = 255;

    function invertColors(data, width) {
      for (var i = 0; i < width*step*10; i+= step) {
        data[i] = 255; // Invert Red
        data[i+1] = 0; // Invert Green
        data[i+2] = 0; // Invert Blue
        data[i+3] = opa;
      }
    }

// 16 = 1*4 + w*4
// 32 = h*4 + h*3*4

	function clearRects(data, t, l , w, h) {
      //for (var i = 0; i < data.length; i+= step) {
        //0,0
      i = 0*4;
        data[i] = 0; // Invert Red
        data[i+1] = 255; // Invert Green
        data[i+2] = 0; // Invert Blue
        data[i+3] = opa;
      //}

      //0,w
      i = (w-1)*4;
        data[i] = 0; // Invert Red
        data[i+1] = 255; // Invert Green
        data[i+2] = 0; // Invert Blue
        data[i+3] = opa;
      //h,0
      i = (h-1)*w*4;
        data[i] = 0; // Invert Red
        data[i+1] = 255; // Invert Green
        data[i+2] = 0; // Invert Blue
        data[i+3] = opa;
      //h,w
      i = (w-1)*4 + (h-1)*w*4;
        data[i] = 0; // Invert Red
        data[i+1] = 255; // Invert Green
        data[i+2] = 0; // Invert Blue
        data[i+3] = opa;
    }

    function checkColors(data, _t, _l, y, x, w, h) {
    	point = (x-_l-1)*4 + (y-_t-1)*w*4;
        //data[point] = 255; // Invert Red
        //data[point+1] = 0; // Invert Green
        //data[point+2] = 0; // Invert Blue
        //data[point+3] = opa;
        //console.log(point);
        return "#" + ("000000" + rgbToHex(data[point], data[point+1], data[point+2])).slice(-6);
    }

    async function run() {
      // load face detection and face landmark models
      await changeFaceDetector(SSD_MOBILENETV1)
      await faceapi.loadFaceLandmarkModel('/')
    }

    $(document).ready(function() {
      run()
    })

    function findRectEye(data){
    	//range[ top, left, bottom, right]
    	var range = [9999,9999,-9999,-9999];
    	for (var i = 0; i < data.length; i++) {
    		if(data[i].x < range[1])
    			range[1] = data[i].x;
    		if(data[i].x > range[3])
    			range[3] = data[i].x;
    		if(data[i].y < range[0])
    			range[0] = data[i].y;
    		if(data[i].y > range[2])
    			range[2] = data[i].y;
    	}
    	return range;
    }

    function rgbToHex(r, g, b) {
	    if (r > 255 || g > 255 || b > 255)
	        throw "Invalid color component";
	    return ((r << 16) | (g << 8) | b).toString(16);
	}

	function getListColor(list){
		var rect = findRectEye(list);
		var tmpcolor1 = [];
    var tmpcolor2 = [];
		var ctx=kanvas.contextContainer.canvas.getContext('2d');
      t_crop = parseInt((rect[0]-2)*scale);
      l_crop = parseInt((rect[1]-2)*scale);
      w_crop = parseInt((rect[3] - rect[1] + 2)*scale);
      h_crop = parseInt((rect[2] - rect[0] + 2)*scale);
	    var imageData = ctx.getImageData(l_crop+3, t_crop+3, w_crop, h_crop); 
	    setTimeout(function(){
	    	kanvas.add(new fabric.Circle({ radius: 2, fill: '#f00', top: rect[0]*scale, left: rect[1]*scale }).set('hasControls', false));
	    	kanvas.add(new fabric.Circle({ radius: 2, fill: '#f00', top: rect[2]*scale, left: rect[3]*scale}).set('hasControls', false));
	    	for (var i = 0; i < list.length; i++) {
		 		 //kanvas.add(new fabric.Circle({ radius: 2, fill: '#00f', top: list[i].y*scale, left: list[i].x*scale}).set('hasControls', false));
		 	  }
	      //clearRects(imageData.data, 0, 0, w_crop, h_crop);
	    },500);
	    setTimeout(function(){
        

        for (var i = 2; i < list.length-1; i+=3) {
	    	  var colorx = checkColors(imageData.data, t_crop, l_crop, parseInt(list[i].y*scale), parseInt(list[i].x*scale), w_crop, h_crop);
          tmpcolor1.push(colorx);
        }
        for (var i = 3; i < list.length-1; i+=3) {
          var colorx = checkColors(imageData.data, t_crop, l_crop, parseInt(list[i].y*scale), parseInt(list[i].x*scale), w_crop, h_crop);
          tmpcolor2.push(colorx);
        }
        
        for (var i = 1; i < list.length-1; i+=3) {
          var circle = new fabric.Circle({id: 'o'+i, radius: 10, top: list[i].y*scale, left: list[i].x*scale});
          circle.set('hasControls', false);
          kanvas.add(circle);
        }

        

	    },1000);
	    setTimeout(function(){
        var j = 0;
        for (var i = 1; i < list.length-1; i+=3) {
          for (var x = 0; x < kanvas._objects.length; x++) {
            if(kanvas._objects[x].id != undefined && kanvas._objects[x].id =='o'+i){
              kanvas._objects[x].setGradient('fill', {
                                                      x1: 20,
                                                      y1: 0,
                                                      x2: 20,
                                                      y2: 20,
                                                      colorStops: {
                                                        0: tmpcolor1[j],
                                                        1: tmpcolor2[j]
                                                      }
                                                    });
              j++;
            }
          }
        }
        kanvas.renderAll();
	    	kanvas.contextContainer.putImageData(imageData, 0, 0);
		    var bs64 = kanvas.contextContainer.canvas.toDataURL();
		    $('#inputImg2').attr('src', bs64);
	    },1500);
	 	

	 	
	 	
	 	
	    
	    //var hex = "#" + ("000000" + rgbToHex(p[0], p[1], p[2])).slice(-6);
	}
  </script>
</body>
</html>