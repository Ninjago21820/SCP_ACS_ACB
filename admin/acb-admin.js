jQuery(document).ready(function($){
    function openMediaDialog($btn, targetTable) {
        var frame = wp.media({ title: 'Select or upload image', library: { type: 'image' }, multiple: false });
        frame.on('select', function(){
            var attachment = frame.state().get('selection').first().toJSON();
            var $row = $btn.closest('tr');
            $row.find('.acb-attachment-id').val(attachment.id);
            var $img = $row.find('img');
            if ($img.length) {
                $img.attr('src', attachment.url).show();
            } else {
                $('<img/>').attr('src', attachment.url).css('max-width','80px').prependTo($row.find('td').eq(2));
            }
        });
        frame.open();
    }

    // add row handlers
    $('#add-containment').on('click', function(e){ e.preventDefault(); $('#containment-table tbody').append('<tr><td><input type="text" name="containment_key[]" value="" /></td><td><input type="text" name="containment_label[]" value="" /></td><td><input type="hidden" name="containment_id[]" class="acb-attachment-id" value="" /><img src="" style="max-width:80px;display:none;" /><button class="button acb-select-media" data-target="containment">Select</button></td><td><button class="button acb-remove-row">Remove</button></td></tr>'); });
    $('#add-risk').on('click', function(e){ e.preventDefault(); $('#risk-table tbody').append('<tr><td><input type="text" name="risk_key[]" value="" /></td><td><input type="text" name="risk_label[]" value="" /></td><td><input type="hidden" name="risk_id[]" class="acb-attachment-id" value="" /><img src="" style="max-width:80px;display:none;" /><button class="button acb-select-media" data-target="risk">Select</button></td><td><button class="button acb-remove-row">Remove</button></td></tr>'); });
    $('#add-disruption').on('click', function(e){ e.preventDefault(); $('#disruption-table tbody').append('<tr><td><input type="text" name="disruption_key[]" value="" /></td><td><input type="text" name="disruption_label[]" value="" /></td><td><input type="hidden" name="disruption_id[]" class="acb-attachment-id" value="" /><img src="" style="max-width:80px;display:none;" /><button class="button acb-select-media" data-target="disruption">Select</button></td><td><button class="button acb-remove-row">Remove</button></td></tr>'); });

    // select media
    $(document).on('click', '.acb-select-media', function(e){ e.preventDefault(); openMediaDialog($(this)); });

    // remove row
    $(document).on('click', '.acb-remove-row', function(e){ e.preventDefault(); $(this).closest('tr').remove(); });

    // On page load: if a row has an attachment id but no visible img, fetch the attachment via wp.media and show preview
    $('input.acb-attachment-id').each(function(){
        var id = $(this).val();
        var $row = $(this).closest('tr');
        var $img = $row.find('img');
        if ( id && id.toString().length > 0 ) {
            try {
                var attachment = wp.media.attachment( parseInt(id) );
                if ( attachment ) {
                    // fetch returns a jQuery deferred in many WP versions
                    attachment.fetch().done(function(){
                        var url = attachment.get('url');
                        if ( url ) {
                            if ( $img.length ) {
                                $img.attr('src', url).show();
                            } else {
                                $('<img/>').attr('src', url).css('max-width','80px').prependTo($row.find('td').eq(2));
                            }
                        }
                    });
                }
            } catch (ex) {
                // fail silently if wp.media API not available
            }
        }
    });
});
