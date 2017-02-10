
//Tabs in uploadedfiles.php
//Must be before documeny.ready
$('.tablinks').click(function(e){
	var tabcontent = 0,
				tablinks = 0;
	var tabName = $(this).attr('data-tabName');
    tabcontent = $('.tabcontent');
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks =  $('.tablinks');
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

		$('#'+tabName).css('display', 'block');
		$('#'+tabName+'Tab').addClass('active');
});

$(document).ready(function(){

	$(window).load(function() {
			$(".se-pre-con").fadeOut("slow");
		});
		
function c(temp){
	console.log(temp);
}



	//INDEX

	$('#upload').click(function(){
		$(this).children('.left').children('form').children('input[type=file]').click();
});

	$('.href').click(function(){
		window.location = $(this).attr('data-href');
		return false;
	});

	$('.clicked').click(function(e){
		e.stopImmediatePropagation();
	});

	$('.right').click(function(e){
		e.stopImmediatePropagation();
	});

	$('#uploadFile').click(function(e){
		e.stopPropagation();
	});

function filesCounterFunc(){
	var filesCounter = $('#uploadInput')[0].files.length;
	return filesCounter;
}

	function checkFileExt(extension, size) {
	if(extension != 'csv'){
		$('#uploadInput').val('');
		$('#filesChosen').text('0');
		$('ul li').remove();
		var li = $("<li></li>").text('ERROR: FILES MUST BE OF TYPE CSV');
		$('.right ul').append(li);
	}
}

function formatBytes(bytes,decimals) {
   if(bytes == 0) return '0 Byte';
   var k = 1000;
   var dm = decimals + 1 || 3;
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
   var i = Math.floor(Math.log(bytes) / Math.log(k));
   return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

$('#uploadInput').change(function(){
	var filesCounter = filesCounterFunc(),
			li = 0, 
			extension = 0, 
			i = 0, 
			file = 0, 
			fileSize = 0, 
			totalFileSize = 0, 
			fileSizeAllowed = 8388608, 
			MB = 0;
	$('#filesChosen').text(filesCounter);

	if(filesCounter > 0 && filesCounter <= 15){

		$('ul li').remove();

		if(!$('#upload').hasClass('clickable'))
			$('#upload').addClass('clickable');

		file = $('#uploadInput')[0].files;
		for (i = 0; i < file.length; i++){
			li = $("<li></li>").text(file[i].name);
			$('.right ul').append(li);
			fileName = file[i].name;
			extension = fileName.split(".");
			checkFileExt(extension[1]);
			if(checkFileExt == true)
				break;
			fileSize = file[i].size;
			totalFileSize += fileSize;
			c('FileSize: ' + fileSize);
		}
		c('TotalFileSize: ' + totalFileSize);
		if(totalFileSize > fileSizeAllowed){
			$('#uploadInput').val('');
			$('ul li').remove();
			MB = formatBytes(totalFileSize, 1);
			li = $("<li></li>").text('Chosen files exceeds the limit of 8.3MB in size. (Current: ' + MB + ')');
			$('.right ul').append(li);
		}
	}
	else if(filesCounter > 15){
		$('#uploadInput').val('');
		$('#filesChosen').text('15+');
		$('ul li').remove();
		li = $("<li></li>").text('MAX 15 FILES AT ONCE');
		$('.right ul').append(li);
	}
	else
		$('ul li').slideUp(200);

	var filesCounter = filesCounterFunc();
	if(filesCounter > 0)
		$('#uploadFile').show();
	else
		$('#uploadFile').hide();

	if(filesCounter == 6)
		$('#upload').removeClass('clickable');
});



$('form#index').on('submit', function(e){
	var filesCounter = filesCounterFunc();
	var file = $('#uploadInput')[0].files;
	var error = 0;
	for (var i = 0; i < file.length; i++){
		var fileName = file[i].name;
		var extension = fileName.split(".");
		if(extension[1] != 'csv') error++;
	}
	if(error > 0 || filesCounter < 1){
			if(error > 0) checkFileExt(extension[1]);
			e.preventDefault();
	}
});


//UPLOADEDFILES
if ($(".processedContent")[0]){
	$('#ProcessTab').click();
	$('#UnprocessedTab').parents('li').addClass('noclick notAllowed');
	$('#HistoryTab').parents('li').addClass('noclick notAllowed');
}
else{
	$('#details').hide();
}


$('.processForm').on('submit', function(e){
	var val = $("input[type=submit][clicked=true]").val();
	if(val == 'Delete selected files'){
		var checkbox = $(this).children('table').children('tbody').children('tr').children('.withCheckbox').children($('.checkbox'))
		
		if(checkbox.is(':checked')){
			$(".se-pre-con").fadeIn("fast");
			$(".se-pre-con").children('h1').text('Processing files, please wait..');
		}
		else{
			e.preventDefault();
			alert('At least one checkbox must be checked to submit');
		}
	}
	else{
		$(".se-pre-con").fadeIn("fast");
		$(".se-pre-con").children('h1').text('Processing files, please wait..');
	}


	
});

$("form input[type=submit]").click(function() {
        $("input[type=submit]", $(this).parents("form")).removeAttr("clicked");
        $(this).attr("clicked", "true");
    });
		
// TRANSACTIONS
 $(document).on('click','.show_more',function(e){
	var shown = $('.list-item').length;
	var results = $(this).text();
	if(results == 'No more results') {
		c('stop');
		$(this).addClass('noclick notAllowed');
		return false;
	}
	c('Shown: ' + shown);
		
	$('.show_more').hide();
	$('.loding').show();
	$.ajax({
			type:'POST',
			url:'ajax_more.php',
			data: 'shown=' + shown,
			success:function(html){
				//c('html: ' + html);
				if(html == ''){
					$('.show_more').text('No more results');
					$('.show_more').show();
					$('.loding').hide();
				}
				else{
					$('.show_more').show();
					$('.loding').hide();
					$('.list').append(html);
				}
			}
	}); 
});


});
