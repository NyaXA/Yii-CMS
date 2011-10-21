<ul class="sub_menu">
     <?php
     $count = count($items);
     for ($i = 0; $i < $count; $i++)
     {
         $item = $items[$i];
         $class = '';
         if ($i == 0)
         {
             $class = 'first';
         }
         if ($i == $count - 1)
         {
             $class = 'last ';
         }
         ?>

         <li class="<?php echo $class ?>">
             <a href="<?php echo $this->url($item->href); ?>"><?php echo $item->title; ?></a>
         </li>
     <?php } ?>
</ul>