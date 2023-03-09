


<tr><td class="text-center">{{$rownum}}</td><td><select class="form-control inboxfield"  @if(isset($inboxwf)) data-selected="{{$inboxwf->Fieldid}}"  @endif  data-row="{{$rownum}}" name="inboxfield[]" required><option value="">Select Field</option></select></td><td><select class="form-control" name="inboxcondition[]" >
                                    <option   value="="  @if(isset($inboxwf) && $inboxwf->Condition=='=') selected @endif >=</option>
                        <option value="<"  @if(isset($inboxwf) && $inboxwf->Condition=='<') selected @endif  ><</option>
                        <option value=">"  @if(isset($inboxwf) && $inboxwf->Condition=='>') selected @endif  >></option>
                        <option value="<>"   @if(isset($inboxwf) && $inboxwf->Condition=='<>') selected @endif   ><></option>
                        <option value="starts_with"   @if(isset($inboxwf) && $inboxwf->Condition=='starts_with') selected @endif >Starts With</option>
                        <option value="ends_with"    @if(isset($inboxwf) && $inboxwf->Condition=='ends_with') selected @endif  >Ends With</option>
                        <option value="contains"   @if(isset($inboxwf) && $inboxwf->Condition=='contains') selected @endif  >Contains</option>
                        <option value="between"    @if(isset($inboxwf) && $inboxwf->Condition=='between') selected @endif   >Between</option>
                        <option value="like"   @if(isset($inboxwf) && $inboxwf->Condition=='like') selected @endif   >Like</option>
                        <option value="notlike"   @if(isset($inboxwf) && $inboxwf->Condition=='notlike') selected @endif   >Not Like</option>

                                    </select></td><td><input type="text" class="form-control" name="inboxvalue[]" @if(isset($inboxwf) ) value="{{$inboxwf->Value}}"  @endif  required/></td><td><select class="form-control" name="fieldinboxstatus[]"  required> 
                                    <option value="">Select Status</option>    
                                    @foreach ($inboxstatuses as $status )
                        <option value="{{$status->id}}"  @if(isset($inboxwf) &&  $inboxwf->statusid==$status->id)  selected  @endif  >{{$status->StatusName}}</option>
                        @endforeach</select></td><td><select class="form-control"  name="inboxconjunction[]" ><option value="Only"    @if(isset($inboxwf) &&  $inboxwf->conjestion=='Only')  selected  @endif  >Only</option><option   @if(isset($inboxwf) &&  $inboxwf->conjestion=='And')  selected  @endif  value="And">And</option><option   @if(isset($inboxwf) &&  $inboxwf->conjestion=='Or')  selected  @endif  value="Or">Or</option></select></td><td class="text-center"><a class="lnk_delete_inbox" href="javascript:void(0);" data-row="{{$rownum}}"><i class="fa fa-lg fa-trash" aria-hidden="true"></i></a></td></tr>