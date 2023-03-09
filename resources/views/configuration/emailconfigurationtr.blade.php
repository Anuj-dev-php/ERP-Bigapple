@if(isset($noofrow))
<tr data-row="{{$noofrow}}">
                        <td><select class="form-control fields" name="field[]" data-row="{{$noofrow}}" required="true" >
                           <option value=""> Select Field</option>
                            @foreach($fields as $field)
                            <option value="{{$field->name}}">{{$field->label}}</option>
                            @endforeach
                        </select></td>
                        <td><select class="form-control" name="condition[]" class="conditions">
                        <option   value="=">=</option>
                        <option value="<"><</option>
                        <option value=">">></option>
                        <option value="<>"><></option>
                        <option value="starts_with">Starts With</option>
                        <option value="ends_with">Ends With</option>
                        <option value="contains">Contains</option> 
                        <option value="like">Like</option>
                        <option value="notlike">Not Like</option>
                        </select></td>
                        <td><input type="text" class="form-control"  name="value[]" class="values"  required /></td>
                        <td><select class="form-control conjs" name="conj[]"   >
                            <!-- <option value="Null">Null</option> -->
                            @if($conj=='And')
                            <option value="And">And</option>
                            @elseif($conj=='Or')
                            <option value="Or">Or</option>
                            @else
                            <option value="Null">Null</option>
                            <option value="And">And</option>
                            <option value="Or">Or</option>
                            @endif
                    </select></td>
                        <td><input type="text" name="email[]" class="emails form-control"   /> </td>
                        <td><input type="text" name="whatsapp[]" class="emails form-control"   /> </td>
                        <td class="text-center"><a class="lnk_delete_row" href="javascript:void(0);"   ><i class="fa fa-lg fa-trash" aria-hidden="true"></i></a></td>

                </tr>
                @else

                @endif