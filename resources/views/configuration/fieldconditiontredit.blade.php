

                    @php $index=1; @endphp
                @foreach($fieldconditions as $fieldcondition)


                <tr><td><select class="form-control fieldnames"  data-row="{{$index}}" name="field_name[]"  required="true" ><option value="">Select Field</option>
@foreach($fields as $field)
<option value="{{$field->name}}"  @if($field->name==$fieldcondition['field_name'])  selected="selected" @endif>{{$field->label}}</option>
@endforeach

</select></td>
                    <td><select   class="form-control condition text-center" name="condition[]"  data-row="{{$index}}" required="true" >
                        <option   value="="   @if($fieldcondition["condition"]=="=") selected="selected"  @endif >=</option>
                        <option value="<"  @if($fieldcondition["condition"]=="<") selected="selected"  @endif ><</option>
                        <option value=">"  @if(strcmp($fieldcondition["condition"],">")==0) selected="selected"  @endif  > &#62; </option>
                        <option value="<>"   @if($fieldcondition["condition"]=="<>") selected="selected"  @endif  >&#60;</option>
                        <option value="starts_with"   @if($fieldcondition["condition"]=="starts_with") selected="selected"  @endif >Starts With</option>
                        <option value="ends_with"    @if($fieldcondition["condition"]=="ends_with") selected="selected"  @endif  >Ends With</option>
                        <option value="contains"     @if($fieldcondition["condition"]=="contains") selected="selected"  @endif >Contains</option>
                        <option value="between"    @if($fieldcondition["condition"]=="between") selected="selected"  @endif  >Between</option>
                        <option value="like"    @if($fieldcondition["condition"]=="like") selected="selected"  @endif  >Like</option>
                        <option value="notlike"     @if($fieldcondition["condition"]=="notlike") selected="selected"  @endif>Not Like</option>
                    </select></td>
                    
                    <td><select class="form-control select2 values"  data-row="{{$index}}"   name="value[]" required="true"  ></select></td><td><select class="form-control restrictfields" data-row="{{$index}}" id="restrict_field_{{$index}}" name="restrict_field[]"  ><option value=''>Select Restrict Field</option>
                    @foreach($fields as $field)
                        <option value="{{$field->name}}"  @if($fieldcondition["rest_field"]==$field->name) selected="selected"   @endif>{{$field->label}}</option>
                    @endforeach
                    </select>
                   </td><td><select class="form-control restrictvalues select2"  data-row="{{$index}}" name="restrict_value[]"  ></select></td><td class="text-center"><a class="lnk_delete_row" href="javascript:void(0);" data-row="{{$index}}"><i class="fa fa-lg fa-trash" aria-hidden="true"></i></a></td></tr>


                @php $index++; @endphp
                @endforeach
