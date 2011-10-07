$(function()
{
    var $object_id = $('#MetaTag_object_id');
    var $model_id  = $('#MetaTag_model_id');

    if (!$object_id.val())
    {
        blockObjectIdElement();
    }

    $model_id.change(function()
    {
        var model_id = $(this).val();

        if (model_id)
        {
            $.getJSON('/main/MainAdmin/getModelObjects', {'model_id' : model_id}, function(objects)
            {
                var options = "";

                if (objects)
                {
                    for (var id in objects)
                    {
                        options+= "<option value='" + id + "'>" + objects[id] + "</option>";
                    }

                    $object_id.html(options);
                }
            });
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
});


