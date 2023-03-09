<tr><td class="text-center">{{$rownum}}</td><td><select class="form-control  savefield" @if(isset($savewf)) data-selected="{{$savewf->Fieldid}}"  @endif  data-row="{{$rownum}}" name="savefield[]" required><option value="">Select Field</option></select></td><td><select class="form-control" name="savecondition[]"  required >  
                                         <option   value="="  @if(isset($savewf)  && $savewf->Condition=='=') selected  @endif >=</option>
                        <option value="<"   @if(isset($savewf)  && $savewf->Condition=='<') selected  @endif><</option>
                        <option value=">"  @if(isset($savewf)  && $savewf->Condition=='>') selected  @endif  >></option>
                        <option value="<>"   @if(isset($savewf)  && $savewf->Condition=='<>') selected  @endif  ><></option>
                        <option value="starts_with"   @if(isset($savewf)  && $savewf->Condition=='starts_with') selected  @endif   >Starts With</option>
                        <option value="ends_with"    @if(isset($savewf)  && $savewf->Condition=='ends_with') selected  @endif   >Ends With</option>
                        <option value="contains"    @if(isset($savewf)  && $savewf->Condition=='contains') selected  @endif    >Contains</option>
                        <option value="like"    @if(isset($savewf)  && $savewf->Condition=='like') selected  @endif   >Like</option>
                        <option value="notlike"    @if(isset($savewf)  && $savewf->Condition=='notlike') selected  @endif   >Not Like</option></select></td>
                        <td><input type="text" class="form-control" name="savevalue[]"   @if(isset($savewf) ) value="{{$savewf->Value}}"  @endif  required/></td><td><select class="form-control savefieldstatus"  data-index="{{$rownum}}" name="savefieldstatus[]"   required >
                         @foreach ($savestatuses as $status ) 

                        <option value="{{$status->id}}"   @if(isset($savewf) &&  $savewf->statusid==$status->id)  selected  @endif  >{{$status->StatusName}}</option>
                    
                        @endforeach
                        </select></td><td><select class="form-control saveconjunction"  data-index="{{$rownum}}" name="saveconjunction[]"  required>

                        @foreach ($conjunctions as $conjunction)
                        <option value="{{$conjunction}}"   @if( (isset($savewf) &&  $savewf->conjestion==$conjunction)   )  selected  @endif  >{{$conjunction}}</option>
                           @endforeach
                         
                        </select></td><td class="text-center"><a class="lnk_delete_save" href="javascript:void(0);" data-row="1"><i class="fa fa-lg fa-trash" aria-hidden="true"></i></a></td></tr>