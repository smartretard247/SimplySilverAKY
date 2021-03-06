<?php $colsArray = array('ID');
    $item_ids = $db->Query('SELECT ID, MAX(PurchaseDate) AS "PurchaseDate"'
                . ' FROM items WHERE ID IN (SELECT ID FROM items WHERE Hidden = 0 AND (QOH-QOO) > 0)'
                . ' GROUP BY ID, PurchaseDate LIMIT 10');
    
    $totalItems['Total'] = 10;

    if($item_ids) :
        
        $numPages = $totalItems['Total'] / $_SESSION['max_per_page'] + 1;
        
        foreach ($item_ids as $tID) :
            $cItem->SetFromDB($tID['ID']);
            $id = $cItem->GetID();
            $input_description = $cItem->GetDescription();
            $input_categoryID = $cItem->GetCategoryID();
            $input_categoryName = $cItem->GetCategoryName();
            $input_categorySingular = $cItem->GetCategorySingular();
            $input_purchasePrice = $cItem->GetPurchasePrice();
            $input_price = $cItem->GetPrice();
            $input_shipping = $cItem->GetShipping();
            $input_purchaseDate = $cItem->GetPurchaseDate();
            $input_QFS = $cItem->GetQuantityForSale();
            $input_thumbPath = $cItem->GetThumbPath();
            
            $cReview->SetFromDB($id);
            $input_rating = $cReview->GetRating();
?>

<?php if($id) : ?>
<table class="topmargin" align="center">
    <tr>
        <td width="<?php echo $_SESSION['thumb_lw']; ?>px">
            <table width="100%" style="border: none;">
                <tr rowspan="2">
                    <td style="border-right: none; padding: none;">
                        <a href="index.php?action=view_item&id=<?php echo $id; ?>">
                            <img src="<?php echo $input_thumbPath; ?>" height="<?php echo $_SESSION['thumb_lw']; ?>px" width="<?php echo $_SESSION['thumb_lw']; ?>px"/>
                        </a>
                    </td>
                </tr>
            </table>
        </td>
        <td width="<?php echo $_SESSION['thumb_lw']*3; ?>px" style="vertical-align: top;">
            <table width="100%">
                <tr>
                    <th colspan="2">Item Information</th>
                </tr>
                <tr>
                    <td style="text-align: center;">
                        <table width="100%">
                            <?php if($input_QFS < 1) : ?>
                                <tr><td colspan="3"><p class="outofstock">This item is currently out of stock.</p></td></tr>
                            <?php endif; ?>
                            <tr>
                                <td class="left" colspan="3"><b>Description:</b> <?php echo $input_description . ' ' . $input_categorySingular; ?></td>
                            </tr>
                            <tr>
                                <td class="left" style="width: 30%;"><b>Price:</b> $<?php echo $input_price; ?></td>
                                <td style="text-align: center; width: 40%;">
                                    <?php if($input_rating) : ?>
                                        <?php ShowStars($input_rating); ?>
                                    <?php else : ?>
                                        <b>Not Yet Rated</b>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right; width: 30%"><b>In Stock:</b> <?php echo $input_QFS; ?></td>
                            </tr>
                        </table>
                        
                        <p style="text-align: right;">
                            <form action="core/add_to_cart.php" method="post">
                                <input type="button" value="View Item Details" onclick="window.location='index.php?action=view_item&id=<?php echo $id; ?>'"/>
                                <?php if($input_QFS): ?>
                                    <input type="hidden" value="<?php echo $id; ?>" name="ID"/>
                                    <input type="hidden" value="<?php echo $input_QFS; ?>" name="QOH"/>
                                    <select name="Q">
                                        <?php for($i = 1; $i <= $input_QFS; $i++) : ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <input type="submit" value="Add To Cart"/>
                                <?php else : ?>
                                    <select disabled="disabled">
                                        <option value="0">0</option>
                                    </select>
                                    <input type="button" value="Add To Cart" disabled="disabled"/>
                                <?php endif; ?>
                            </form>
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php endif; ?>
<?php endforeach; ?>


<?php else : ?>
<table align="center">
    <tr>
        <th>Sold Out</th>
    </tr>
    <tr>
        <td>
            <p align="center">Sorry, we do not have any items in that category.</p>
        </td>
    </tr>
</table>
<?php endif; ?>