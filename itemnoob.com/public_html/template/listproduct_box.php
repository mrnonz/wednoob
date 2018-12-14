<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 product-list">
    <table class="table table-bordered">
        <tr>
            <td class="text-center" style="padding: 0px; border-color:#da4453; background-color: #fff">
                <div class="thumbnail" style="margin: 0px;">
                    <a href='product.php?id=<?php echo $product['product_id']; ?>' title="<?php echo $product['product_name']; ?>">
                        <img style="border-radius: 8px 8px 8px 8px;" src="images/product/<?php echo $product['product_image']; ?>" alt="<?php echo $product['product_name']; ?>"/>
                    </a>
                </div>
            </td>
        </tr>
        <tr>
            <td class="pricesize-productlist" style="background-color: #fff"><strong><a href='product.php?id=<?php echo $product['product_id']; ?>' title="<?php echo $product['product_name']; ?>"><?=substr($product['product_name'],0,24);?></a></strong></td>
        </tr>
        <?/*
        <tr>
            <td class="text-center" style="background-color: #fff">
                <?
                    $unit_product = explode("-", $product['product_rate']);
                    if($product['product_rate'] == "")
                    {
                        $product_rate_price = "0";
                    }
                    elseif($unit_product[0] == "00")
                    {
                        $product_rate_price = "1";
                    }
                    else
                    {
                        $product_rate_price = $unit_product[0];
                    }
                ?>
                <div class="pricesize-productlist">ราคา <? echo $product['product_price']; ?> <span class="price_baht">บาท</span> = <?php echo $product_rate_price; ?> <? echo $unit_product[1]; ?>
                <?php
                    if($product['product_sale_status'] == '1'){
                        echo '<span class="label label-success">ลดราคา</span>';
                    }
                ?>
                </div>
            </td>
        </tr>
        */?>
        <tr>
            <td class="text-center">
                <button class="btn btn-default btn-block btn-sm" style="border-color:#ed5565; color:#ed5565;" type="button" onclick="location.href='product.php?id=<?php echo $product['product_id']; ?>';">
                    <i class="fa fa-shopping-basket" aria-hidden="true"></i> สั่งซื้อสินค้า
                </button>
            </td>
        </tr>
    </table>
</div>