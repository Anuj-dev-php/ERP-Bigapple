@inject('controller','App\Http\Controllers\Configuration\TransactionActionRolesController')
@php
 $detailindex=0; 
 $noofdetailfields=count($detailfields);
@endphp
 
@for ($i=0;$i<$subdetailscount;$i++)
<tr  id='tr_{{$i+1}}'>
<input type="hidden" name="data_sub_det[{{$i}}][Id]" data-subfieldname="id"  data-issubdet="1" data-row="{{$i+1}}"  value="" />
	 
<td   style='min-width:10px'  >
                                      <label class='lbl_control text-center' style='font-weight:bold;' >{{$i+1}}</label>
                                    </td>  	
@foreach($detailfields as $detailfield) 
							 		@php
									  $detailindex++; 
									  
                                      $fieldwidth=$detailfield->Width;

                                      if(empty($fieldwidth)){

                                        $fieldwidthpx=$fieldwidth;
                                      }
                                      else{
                                        $fieldwidthpx='200'; 
                                      }


									 @endphp 
						
								@if($detailfield->Field_Function==1)
								<td    class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
									<input type="text"    name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"   class="form-control      @if(!empty($detailfield->get_tot) && trim($detailfield->get_tot)=='True'  ) gettotal  @endif"  data-issubdet="1" data-row='{{$i+1}}' name="field"  @if($detailfield->Field_Name!='descr') value='0'  @endif  data-subfieldname="{{strtolower($detailfield->Field_Name)}}"    @if(trim($detailfield->Field_Name)!="descr")  required @endif  @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif   /> </td> 
									@elseif ( $detailfield->Field_Function=='11')
								<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
									<input type="text"    name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"   class="form-control function11 " data-issubdet="1"  data-row='{{$i+1}}'  data-subfieldname="{{strtolower($detailfield->Field_Name)}}"   @if($detailfield->Field_Name!='descr') value='0'  @endif   @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif       required /> </td> 
								 
									@elseif ($detailfield->Field_Function==2)
								<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
									<select    name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"   class="form-control   function2 "  data-issubdet="1"  data-row='{{$i+1}}'   data-subfieldname="{{strtolower($detailfield->Field_Name)}}"    @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif   required>
										<option>Select From Ajax suggestion box</option>
									</select>
								</td> @elseif ($detailfield->Field_Function==4)
								<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
									<select    name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"   class="form-control function4   "  data-issubdet="1"  data-row='{{$i+1}}'  data-subfieldname="{{strtolower($detailfield->Field_Name)}}"     @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required></select>
								</td> @elseif ($detailfield->Field_Function==5)
								<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
									<select     name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"   class="form-control function5  "  data-issubdet="1"  data-row='{{$i+1}}'  data-subfieldname="{{strtolower($detailfield->Field_Name)}}"  @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required> </select>
								</td> @elseif ($detailfield->Field_Function==6)
								<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
									<input type="text"   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class="form-control function6 "  data-issubdet="1"  data-row='{{$i+1}}'   placeholder="Select Date Calendar"   data-subfieldname="{{strtolower($detailfield->Field_Name)}}"   value="{{date('Y-m-d',strtotime('now'))}}"     @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif   required/>
								</td>
                @elseif ($detailfield->Field_Function==8)
                <td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
									<input type="file"   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class="form-control  "  data-issubdet="1"  data-row='{{$i+1}}'   data-subfieldname="{{strtolower($detailfield->Field_Name)}}"   @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif   required />
								</td>
                @elseif ($detailfield->Field_Function==19)
                <td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
                <div class="divfunction19  "  data-subfieldname="{{strtolower($detailfield->Field_Name)}}"  data-issubdet="1"  data-row='{{$i+1}}'  > 
                </div>
                </td>
                    @elseif ($detailfield->Field_Function==27)
                    <td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
                    <input type='text'   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"   class="form-control  "  data-issubdet="1"   data-subfieldname="{{strtolower($detailfield->Field_Name)}}"  data-row='{{$i+1}}'     value="<?php echo date('Y-m-d h:i:s',strtotime('now'));  ?>"    @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required/>
                    </td>
                    @elseif ($detailfield->Field_Function==31)
                    <td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
                    <input type="datetime-local"   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class='form-control  '  data-issubdet="1"  data-row='{{$i+1}}'   data-subfieldname="{{strtolower($detailfield->Field_Name)}}"   placeholder='function31' value="{{date('Y-m-d H:i:s',strtotime('now'))}}"   @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required/>
                    </td>
                    @elseif($detailfield->Field_Function==40)
                      <td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
                    <input type="file"    name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"   class="form-control  "  data-issubdet="1"  data-row='{{$i+1}}'   data-subfieldname="{{strtolower($detailfield->Field_Name)}}"   accept="image/*"    @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required/>
                      </td>
                      @elseif($detailfield->Field_Function==18)
                      <td    class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
                      <select   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class="form-control function18  "  data-issubdet="1" data-row='{{$i+1}}'   data-subfieldname="{{strtolower($detailfield->Field_Name)}}"   @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required></select>  
                      </td>
                      @elseif($detailfield->Field_Function==22)
                      <td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
                      <input type='text'   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  data-subfieldname="{{$detailfield->Field_Name}}"  data-row='{{$i+1}}'  data-issubdet="1"  class='form-control function22  '    readonly='true'  required />
                      </td>
                      @elseif($detailfield->Field_Function==20)
                      <td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
                      <select   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class='form-control function20  '  data-issubdet="1"  data-row='{{$i+1}}'   data-subfieldname="{{strtolower($detailfield->Field_Name)}}"   @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif   required></select>
                      </td>
					  @elseif($detailfield->Field_Function==3)
					  <td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'> 
						  	<select    name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class="form-control function3  "   data-row='{{$i+1}}'  data-issubdet="1"  data-subfieldname="{{strtolower($detailfield->Field_Name)}}"    @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required></select>
							</td> 
						@elseif($detailfield->Field_Function==14)
						<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
							<select   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class='form-control function14  '  data-row='{{$i+1}}'    data-issubdet="1"  data-subfieldname="{{strtolower($detailfield->Field_Name)}}"    @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required ></select>
						</td>
						@elseif($detailfield->Field_Function==15)
						<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'><input   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  type='text' class='form-control function15   '  data-row='{{$i+1}}'   data-issubdet="1"  data-subfieldname="{{strtolower($detailfield->Field_Name)}}" @if($detailfield->Field_Name!='descr') value='0'  @endif  data-datefield="{{$detailfield->Field_Value}}"       @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required /></td>
						@elseif($detailfield->Field_Function==16)
						<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
							<select   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class='form-control function16  '   data-row='{{$i+1}}'   data-issubdet="1"  data-subfieldname="{{strtolower($detailfield->Field_Name)}}"     @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required></select>
						</td>
						@elseif($detailfield->Field_Function==24)
						<td    class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
							<select   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class='form-control function24  '  data-row='{{$i+1}}'    data-issubdet="1"  data-subfieldname="{{strtolower($detailfield->Field_Name)}}"    @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required></select>
						</td>
						@elseif($detailfield->Field_Function==34)
						<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
							<textarea   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class='form-control function34  '   data-row='{{$i+1}}'  data-issubdet="1"  data-subfieldname="{{strtolower($detailfield->Field_Name)}}"   placeholder='Function34'  @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required></textarea>
						</td>
						@elseif($detailfield->Field_Function==21  && empty($detailfield->Field_Value))
						<td    class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
						<input type='text'   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class='form-control function21  '  data-row='{{$i+1}}'   data-issubdet="1"    data-hasfieldvalue='0' data-subfieldname="{{strtolower($detailfield->Field_Name)}}"   data-issubdet="1" @if($detailfield->Field_Name!='descr') value='0'  @endif  placeholder='Function21'    @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required/>
		
						</td>
						@elseif($detailfield->Field_Function==21   && !empty($detailfield->Field_Value))
						<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
						<input type='text'   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"   class='form-control function21  '  data-row='{{$i+1}}'   data-issubdet="1"    data-hasfieldvalue='1' data-subfieldname="{{strtolower($detailfield->Field_Name)}}" @if($detailfield->Field_Name!='descr') value='0'  @endif    data-issubdet="1"  placeholder='Function21'  @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required/>
		
						</td>
                        @elseif($detailfield->Field_Function==45  && empty($detailfield->Field_Value))
						<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
						<input type='text'   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class='form-control function45  '  data-row='{{$i+1}}'   data-issubdet="1"    data-hasfieldvalue='0' data-subfieldname="{{strtolower($detailfield->Field_Name)}}"   data-issubdet="1" @if($detailfield->Field_Name!='descr') value='0'  @endif  placeholder='Function45'  @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif required/>
		
						</td>

						 
						@elseif($detailfield->Field_Function==45   && !empty($detailfield->Field_Value))
						<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
						<input type='text'   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"   class='form-control function45  '   data-row='{{$i+1}}'   data-issubdet="1"    data-hasfieldvalue='1' data-subfieldname="{{strtolower($detailfield->Field_Name)}}"   @if($detailfield->Field_Name!='descr') value='0'  @endif  placeholder='Function45'  @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif  required/>
		
						</td>

						@elseif($detailfield->Field_Function==30   )
						<td   class="@if($controller::CheckFieldDisplay($detailfield->Field_Name,$showhide_detfields)) d-none @endif"  style='min-width:{{$fieldwidthpx}}px'>
						    <select   name="data_sub_det[{{$i}}][{{$detailfield->Field_Name}}]"  class='form-control function30 '   data-row='{{$i+1}}'   data-issubdet="1"  data-subfieldname="{{strtolower($detailfield->Field_Name)}}"    placeholder='Function30'  @if($controller::CheckFieldReadOnly($detailfield->Field_Name,$showhide_detfields,$detailfield->rd_only)) readonly @endif   required></select>
							</td>
                        
                    @endif
 
                 @endforeach 
				<td class='text-center'>
				<a class="lnk_delete_subdetail_row lastdetailelement" href="javascript:void(0);"  data-row='{{$i+1}}'    ><i class="fa fa-lg fa-trash" aria-hidden="true"></i></a>
				</td>

</tr>
    
@endfor


 