$(function()
{
    var $object_id   = $('#MetaTag_object_id');
    var $model_id    = $('#MetaTag_model_id');
    var $meta_tag_id = $('#MetaTag_id');
    alert($model_id.val());
    if ($model_id.val())
    {
        loadModelObjects($model_id.val(), $meta_tag_id.val());
    }
    else
    {
        blockObjectIdElement();
    }

    $model_id.change(function()
    {
        var model_id = $(this).val();

        if (model_id)
        {
            loadModelObjects(model_id);
        }
        else
        {
            blockObjectIdElement();
        }
    });


    function blockObjectIdElement()
    {
        $object_id.html('<option>выберите модель</option>');
        $object_id.attr('disabled', true);
    }


    function loadModelObjects(model_id, object_id)
    {
        $.getJSON('/main/MetaTagAdmin/getModelObjects', {'model_id' : model_id, 'object_id' : object_id}, function(objects)
        {
            var options = "<option value=''>не выбран</option>";

            if (objects)
            {
                for (var id in objects)
                {
                    options+= "<option value='" + id + "'>" + objects[id] + "</option>";
                }

                $object_id.html(options);
                $object_id.attr('disabled', false);
            }
        });
    }
});


