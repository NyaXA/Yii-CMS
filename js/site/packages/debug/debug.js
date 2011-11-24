/*
 print_r подобная функция с красивым выводом
 распечатывает как массвы так и объекты
 */
function dump(arr, maxlevel)
{
    if (!maxlevel)
    {
        maxlevel = 10;
    }

    var design = {position:"fixed", top:"30%", left:"40%", background:"#EEE", color:"#333", "min-width":"400 px", "min-height":"400 px", display:"none", border:'1px #333 solid'};

    function removeAnimate()
    {
        $(this).fadeOut('fast', function()
        {
            $(this).remove();
        });
    }

    //сам вывод
    $('<div>')
        .css(design)
        .appendTo("body")
        .click(removeAnimate)
        .html('<pre>' + dump_rec(arr, 0, maxlevel) + '</pre>')
        .fadeIn('fast');
}

function dump_rec(arr, level, maxlevel)
{
    var dumped_text = "";

    if (level <= maxlevel)
    {
        //The padding given at the beginning of the line.
        var level_padding = "";
        for (var j = 0; j < level + 1; j++)
        {
            level_padding += "    ";
        }

        if (typeof(arr) == 'object')
        { //Array/Hashes/Objects
            for (var item in arr)
            {
                var value = arr[item];

                if (typeof(value) == 'object')
                { //If it is an array,
                    dumped_text += level_padding + "'" + item + "' ...\n";
                    dumped_text += dump_rec(value, level + 1, maxlevel);
                }
                else
                {
                    dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
                }
            }
        }
        else
        { //Stings/Chars/Numbers etc.
            dumped_text = "===>" + arr + "<===(" + typeof(arr) + ")";
        }
    }
    return dumped_text;
}

