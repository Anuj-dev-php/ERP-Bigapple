<tr >
    <td><select class='form-control' name='whatsapp_custom_field_id[]' required>
        <option value=''>Select Custom Field</option>
        @foreach ($custom_fields as $custom_field)
            <option value="{{$custom_field['id']}}">{{$custom_field['name']}}</option>
        @endforeach
    </select></td>
    <td><input type='text' class='form-control'  name='whatsapp_custom_field_name[]' required/></td>
    <td><a href='javascript:void(0);' class='link_delete_customfield' data-noofcustomfield="{{$noofcustomfield}}"><i class="bi bi-trash"></i></a></td>
</tr>