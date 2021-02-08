function eyeshow(e){
	is_eyeshow *= -1;
}
function lipshow(e){
	is_lipshow *= -1;
}
function gridshow(e){
	is_gridshow *= -1;
}

function setColor(e){
	active_color = e;
}

function processDown(){
	if(is_eyeshow == -1){
		getDotColor();
	} else {
		blurEye();
	}
}

function selectEyeBrown(i){
	if(eyeclone == null){
		addEyeBrown(i);
	}
	else{
		eyeclone.setSrc('resource/'+eyeresource[i]);
		eyeorigin.setSrc('resource/'+eyeresource[i]);
	}

	kanvas.renderAll();
}