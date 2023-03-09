function SnackbarMsg(data){



    var data=JSON.parse(JSON.stringify(data));
    var message=data['message'];

 
     
toastr.options = {
    closeButton: false,
    debug: false,
    newestOnTop:false,
    progressBar: true,
    positionClass:  'toast-top-right',
    preventDuplicates:false, 
    showEasing:'swing',
    showDuration:10,
    hideEasing:'linear',
    hideDuration:1000,
    showMethod:'fadeIn',
    timeOut:5000,
    hideMethod:'fadeOut',
    extendedTimeOut:1000 ,
    tapToDismiss:false

}; 





    if(  data['status']==true ||  data['status']=='success'){
              
        // SnackBar({ 
        //     message: message,status:'success'
        // })  
            var $toast = toastr['success'](message, ''); // Wire up an event handler to a button in the toast, if it exists
            $toastlast = $toast;

            return true;
                   
        // if(typeof $toast === 'undefined'){
        //     return;
        // }



    }
    else{
        
        // SnackBar({ 
        //     message: message,status:'error'
        // });

        var $toast = toastr['error']( message, ''); // Wire up an event handler to a button in the toast, if it exists
        $toastlast = $toast;

        return false;
        
        // if(typeof $toast === 'undefined'){
        //     return;
        // }
        

    } 
}


function initSelect2Search(element,url,placeholder,exceptid=null,data={},parent='body'){
    $(element).empty();
    $(element).select2({
        dropdownParent: $(parent),
        allowClear: true,
        placeholder:placeholder ,
        allowClear:true,
        ajax: { 
        url: url, 
        type: "post",
        dataType: 'json',
        delay:100,
        data: function (params) {
            return {
            searchTerm: params.term, // search term
            except_id:exceptid,
            data:data
            };
        },
        processResults: function (response) {
            return {
                results: response
            };
        },
        cache: true
        }, 
      formatResult: function(element){
          return element.text + ' (' + element.id + ')';
      },
      formatSelection: function(element){
          return element.text + ' (' + element.id + ')';
      },
      escapeMarkup: function(m) {
          return m;
      }
        });

}
 

function initSelect2SearchTriggerChange(element,url,placeholder,exceptid=null,data={}){
    $(element).empty();
    $(element).select2({
        allowClear: true,
        placeholder:placeholder ,
        allowClear:true,
        ajax: { 
        url: url, 
        type: "post",
        dataType: 'json',
        delay:100,
        data: function (params) {
            return {
            searchTerm: params.term, // search term
            except_id:exceptid,
            data:data
            };
        },
        processResults: function (response) {
            return {
                results: response
            };
        },
        cache: true
        }, 
      formatResult: function(element){
          return element.text + ' (' + element.id + ')';
      },
      formatSelection: function(element){
          return element.text + ' (' + element.id + ')';
      },
      escapeMarkup: function(m) {
          return m;
      }
        }).trigger("change");

}
 
 
$(document).ready(function(){
    var submitbtn= $("input[type='submit']");
    submitbtn.attr("disabled", false);
  
    $("form").submit(function(){
      submitbtn.attr("disabled", true).val("Processing...");

      setTimeout(function(){
        submitbtn.attr("disabled", false).val("Submit");
      }, 5000);



      return true;
    })
  })


  function initSelect2WithOnlyOneOption(element, placeholder,optionid,optiontext,parent='body'){
    $(element).empty();

    $(element).append(`<option selected='selected' value="${optionid}">${optiontext}</option>`);

    $(element).select2({ 
        dropdownParent: $(parent), 
        allowClear: false,
        placeholder:placeholder });; 

  }


  function initSelect2WithOnlyOneOptionWithAddOption(element, placeholder,optionid,optiontext){
    $(element).empty();

    $(element).append(`<option selected='selected' value="${optionid}">${optiontext}</option>`);

    $(element).select2({
       tags:true}); 

  }



function showLoader(noofseconds){

    

    if($("#loader").css("display")=='block')
    return ;

           $("body").css("pointer-events","none"); 
    
            $("#loader").show();
            var i = 0;
            var counterdiff= 100/noofseconds;

            var counterBack = setInterval(function () { 
            i=Math.floor(i+counterdiff);
            if (i <101) {
                $('.progress-bar').css('width', (i+counterdiff) + '%');
                $('.progress-value').html(  i + '%');
            } else {
                clearInterval(counterBack);
            }

            if(i>=100){

                setTimeout(function(){
                    $("#loader").hide();
                    $('.progress-bar').css('width','0%');
                    $('.progress-value').html('0%');
                   $("body").css("pointer-events","auto"); 
                },1000);
            }

            }, 1000);

  }


  function initTabOnSelect2(parent=''){

                // on first focus (bubbles up to document), open the menu

            if(parent==''){
                $(document).on('focus', '.select2-selection.select2-selection--single', function (e) { 
                    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
                     
                });
  
                // steal focus during close - only capture once and stop propogation
                // $('select.select2').on('select2:closing', function (e) {
                //     $(e.target).data("select2").$selection.one('focus focusin', function (e) {
                //     e.stopPropagation();
                //     });
                // });
    
                $(document).on('select2:open', () => { 
                    // started this on 210692022
                    document.querySelector('.select2-search__field').focus();

                    $(this).find('.select2-search__field').focus();;
                  });
            }
            else{

                
                $("#subdetailEnterModal").on('focus', '.select2-selection.select2-selection--single', function (e) {
                    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
                     
                });

                // $("#subdetailEnterModal").on('blur', '.select2-selection.select2-selection--single', function (e) {
                //     $(this).closest(".select2-container").siblings('select:enabled').select2('close');
                     
                // });
                
                
                // steal focus during close - only capture once and stop propogation
                // $('#subdetailEnterModal select.select2').on('select2:closing', function (e) {
                //     $(e.target).data("select2").$selection.one('focus focusin', function (e) {
                //     e.stopPropagation();
                //     });
                // });
    
                // $("#subdetailEnterModal").on('select2:open', () => {
                //     $('#subdetailEnterModal  .select2-search__field').focus();
                //   });

            }
       

  }


  function addSelect2SelectedOption(element,fielddisplay,fieldvalue){

    $(element).find(":selected").removeAttr('selected');
    $(element).empty();
    $(element).append(`<option value='${fieldvalue}' selected>${fielddisplay}</option>`);
   
    // $(element).trigger('change');
 
  }


  function addSelect2SelectedOptionTriggerChange(element,fielddisplay,fieldvalue){

    $(element).find(":selected").removeAttr('selected');
    $(element).empty();
    $(element).append(`<option value='${fieldvalue}' selected>${fielddisplay}</option>`);
    $(element).trigger('change');
 
  }



  function alertUserMsg(title,content){
	$.alert({
		title:'<h5><b>'+title+'</b></h5>',
		content: '<h6>'+content+'</h6>',
		columnClass: 'col-md-6',
        type: 'green',
		buttons: { 
			ok: {
            text: "ok",
			btnClass: 'btn-primary',
			}

		} 
	});

  }
  
  
  function confirmUserMsg(title,content,successfunc=function(){ return true; },failurefunc=function(){return false },yesbuttonname='Ok!',nobuttonname='Cancel'){
    $.confirm({
        title: '<h5><b>'+title+'</b></h5>',
        content: '<h6>'+content+'</h6>',
        columnClass: 'col-md-6',
        width:400,
        type: 'green',
        buttons: {   
            ok: {
                text: yesbuttonname,
                btnClass: 'btn-primary',
                keys: ['enter'],
                action: function(){
                    successfunc();
                }
             
            },
            cancel: {
                text: nobuttonname,
                btnClass: 'btn-primary',
                action: function(){
                    failurefunc();
                   }
            }
        }
    });

  }

  function printFromHtml(htmlgiven){

    
    var a = window.open('', '', 'height=1200, width=700');
    a.document.write('<html>');
    var htmldesign = '' +
    '<style type="text/css">' +
    'table{ border-collapse: collapse; }'+
    ' table th, table td {' +
    'border:1px solid #000;' +
    'padding:10px;' +
    '}' +
    '</style>';

    a.document.write(htmldesign);
 
    a.document.write('<body >  ');
    a.document.write(htmlgiven);
    a.document.write('</body></html>');
    a.document.close();
    a.print();

  }


    


