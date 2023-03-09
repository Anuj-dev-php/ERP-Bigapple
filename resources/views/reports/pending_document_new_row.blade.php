@php
    
function showHeaderField($header_field){

    $header_field=str_replace('_',' ',$header_field);

    
    $header_field=ucfirst(  $header_field);
    
    return $header_field;
}
@endphp
  
<div class="row searchfieldrow"  data-index='{{$noofsearchfield}}'>
 
 <div class="form-group col-2 searchdiv_field">
         <label class="lbl_control ">Field:</label> 
         <div  >
             <select class="form-control searchfield" name="searchfield[]"   data-index="{{$noofsearchfield}}">
                 <!-- <option value="">Select Field</option>  -->

                 @if(count( $headerfields))

                 @foreach ($headerfields as $field )
                     <option value="{{$field}}"   >{{ showHeaderField($field) }}</option>
                 @endforeach

                 @endif



                    </select>
                 </div>
     </div>
     <div class="form-group col-1  searchdiv_condition ">
         <label class="lbl_control">Condition:</label>
         <div>
             <select class="form-control searchcondition" name="searchcondition[]" data-index="{{$noofsearchfield}}">
             <option value="<"><</option>
                 <option value=">">></option>
                 <option value="=">=</option>
                 <option value="!=">!=</option>
                 <option value="Like">Like</option>
                 <option value="Not Like">Not Like</option> 
                 <option value="Contains">Contains</option>
                 <option value="Begin With">Begin With</option>
                 <option value="Ends With">Ends With</option>
             </select>
         </div>
     </div>
     <div class="form-group col-2   searchdiv_value " data-index="{{$noofsearchfield}}">
         <label class="lbl_control">Value:</label>
         <div> 
         <input type='text' name="searchval[]" class='form-control searchval'  data-index="{{$noofsearchfield}}"   required />

         </div>
      
     </div>

     <div class=" form-group col-1   searchdiv_operator " data-index="{{$noofsearchfield}}">
         <label class="lbl_control">Operator:</label>
         <div>
             <select name="searchoperator" class='form-control searchoperator' data-index="{{$noofsearchfield}}" required>
                 <option value="And">And</option>
                 <option value="Or">Or</option>
             </select>
         </div>
     </div>

     <div class=" form-group col-1" data-index="{{$noofsearchfield}}">
         <label class="lbl_control">Delete:</label>
         <div style='vertical-align:middle;'>
         <a   data-index="{{$noofsearchfield}}" href='javascript:void(0);'   class='delete-link'>	<i class="fa fa-trash" aria-hidden="true"></i></a>
  
         </div>
     </div> 

     </div>