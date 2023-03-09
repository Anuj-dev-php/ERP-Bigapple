  
<tr><td><select class="form-control fieldnames"  data-row="{{$rownum}}" name="field_name[]"  required="true" ><option value="">Select Field</option>
@foreach($fields as $field)
<option value="{{$field->name}}">{{$field->label}}</option>
@endforeach

</select></td>
                    <td><select   class="form-control condition text-center" name="condition[]"  data-row="{{$rownum}}" required="true" >
                        <option   value="=">=</option>
                        <option value="<"><</option>
                        <option value=">">></option>
                        <option value="<>"><></option>
                        <option value="starts_with">Starts With</option>
                        <option value="ends_with">Ends With</option>
                        <option value="contains">Contains</option>
                        <option value="between">Between</option>
                        <option value="like">Like</option>
                        <option value="notlike">Not Like</option>
                    </select></td>
                    
                    <td><select class="form-control select2 values"  data-row="{{$rownum}}"   name="value[]" required="true"  ><option value="">Select Value</option></select></td><td><select class="form-control restrictfields" data-row="{{$rownum}}" id="restrict_field_{{$rownum}}" name="restrict_field[]"  ><option value=''>Select Restrict Field</option>
                    @foreach($fields as $field)
<option value="{{$field->name}}">{{$field->label}}</option>
@endforeach

                </select></td><td><select class="form-control restrictvalues select2"  data-row="{{$rownum}}" name="restrict_value[]"  ><option value=''>Select Restrict Value</option></select></td><td class="text-center"><a class="lnk_delete_row" href="javascript:void(0);" data-row="{{$rownum}}"><i class="fa fa-lg fa-trash" aria-hidden="true"></i></a></td></tr>

        
 