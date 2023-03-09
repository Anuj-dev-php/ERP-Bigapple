<div class="row searchfieldrow"   data-index='{{$noofsearch}}'>
 
 <div class="form-group col-2 searchdiv_field">
         <label class="lbl_control ">Field:</label> 
         <div  >
             <select class="form-control searchfield" name="searchfield[]" id="ddnFirstField" data-index="{{$noofsearch}}" required> 
                <option value=''>Select Field</option>
                 @foreach ($fields as $field )
                     <option value="{{$field->Field_Name}}"  data-function="{{$field->Field_Function}}">{{$field->fld_label}}</option>
                 @endforeach
                     </select>
                 </div>
     </div>
     <div class="form-group col-1  searchdiv_condition ">
         <label class="lbl_control">Condition:</label>
         <div>
             <select class="form-control searchcondition" name="searchcondition[]" data-index="{{$noofsearch}}">
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
     <div class="form-group col-2   searchdiv_value " data-index="{{$noofsearch}}">
         <label class="lbl_control">Value:</label>
         <div>
             <select name="searchval[]" class='form-control  searchval' data-index="{{$noofsearch}}"   required></select>
         </div>
      
     </div>
     <div class=" form-group col-1   searchdiv_operator " data-index="{{$noofsearch}}">
         <label class="lbl_control">Operator:</label>
         <div>
             <select name="searchoperator" class='form-control searchoperator' data-index="{{$noofsearch}}" required>
                 <option value="And">And</option>
                 <option value="Or">Or</option>
             </select>
         </div>
     </div>
 
     <div class='form-group col-1   '  data-index="{{$noofsearch}}">
     <label class="lbl_control">Delete:</label>
     <div>
					<a   data-index="{{$noofsearch}}" href='javascript:void(0);'   class='delete-link'>	<i class="fa fa-trash" aria-hidden="true"></i></a>
                    </div>
                </div> 


     </div>