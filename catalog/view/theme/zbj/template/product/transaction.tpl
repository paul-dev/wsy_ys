<?php if ($transactions) { ?>
<table class="deal-all w100"> 
    <thead class="j-deal-comments deal-head"> 
        <tr> 
            <th class="deal-buyer">买家</th> 
            <th class="deal-num">成交数量</th>
            <th class="deal-time">成交时间</th> 
            <th class="deal-style">型号款式</th> 
        </tr> 
    </thead> 
</table>
<table class="deal-all w100"> 
    <tbody class="deal-comments j-deal-comments" id="j-deal-comments">
        <?php foreach ($transactions as $transaction) { ?>
        <tr>
            <td class="username"><img src="<?php echo $transaction['avatar']; ?>" alt="<?php echo $transaction['name']; ?>" width="32" height="32"><span><?php echo $transaction['name']; ?></span></td>
            <td><?php echo $transaction['quantity']; ?></td>
            <td><?php echo $transaction['date_added']; ?></td>
            <td class="deal-style">
                <?php if ($transaction['model']) { ?>
                <?php echo $transaction['model']; ?><br/>
                <?php } ?>
                <?php foreach ($transaction['options'] as $option) { ?>
                <?php echo $option['name']; ?>: <?php echo $option['value']; ?>
                <br/>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<div class="text-right" style="margin-top:10px;"><?php echo $pagination; ?></div>
<?php } else { ?>
<p><?php echo $text_no_transaction; ?></p>
<?php } ?>