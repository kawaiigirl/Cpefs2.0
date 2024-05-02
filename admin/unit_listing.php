<?php
include "include/admin_header.php";
include_once "redirect_to_adminlogin.php";

$_SESSION['backpage'] = "unit_listing.php";

$f = 0;
$op = 0;
$sub = "";
$sun = "";
$sue = "";
global $dbc;
$qryParamTypes = "";
$qryParams = array();
if(isset($_POST['submit']))
{
    /* Multiple Deletion */
    if(isset($_POST['delete']) && $_POST['delete'] != "")
    {
        foreach($_POST['delete'] as $del)
        {
            $dbc->delete("Delete from cpefs_units where unit_id = ?", __LINE__, __FILE__, array("i", &$del));
        }
    }
    $op = 2;
}
elseif(count($_POST) > 0 || isset($_GET['sun']) || isset($_GET['sue']))
{
    if(count($_POST) > 0)
    {
        $_GET['sun'] = $_POST['s_unit_name'];
        $_GET['sue'] = $_POST['s_unit_email'];
    }
    if($_GET['sun'] != "")
    {
        $sun = $_GET['sun'];
        $sub = " and unit_name like ?";
        $qryParamTypes .= "s";
        $qryParams[] = "%" . $sun . "%";
    }
    if($_GET['sue'] != "")
    {
        $sue = $_GET['sue'];
        $sub .= " and manager_email = ?";
        $qryParamTypes .= "s";
        $qryParams[] = $sue;
    }
}
$ord_next_name = 1;
$ord_next_basic = 3;
$ord_next_peak = 5;
$ord_next_week = 7;
$ord_next_email = 9;
$ord = "";
$img_name = "down";
if(isset($_GET["ord"]))
{
    if($_GET["ord"] == 1)
    {
        $ord = " unit_name ASC";
        $img_name = "up";
        $ord_next_name = 2;
    }
    elseif($_GET["ord"] == 2)
    {
        $ord = " unit_name DESC";
        $img_name = "down";
    }
    elseif($_GET["ord"] == 3)
    {
        $ord = " basic_rate ASC";
        $img_name = "up";
        $ord_next_basic = 4;
    }
    elseif($_GET["ord"] == 4)
    {
        $ord = " basic_rate DESC";
        $img_name = "down";
    }
    elseif($_GET["ord"] == 5)
    {
        $ord = " peak_rate ASC";
        $img_name = "up";
        $ord_next_peak = 6;
    }
    elseif($_GET["ord"] == 6)
    {
        $ord = " peak_rate DESC";
        $img_name = "down";
    }
    elseif($_GET["ord"] == 7)
    {
        $ord = " weekend_rate ASC";
        $img_name = "up";
        $ord_next_week = 8;
    }
    elseif($_GET["ord"] == 8)
    {
        $ord = " weekend_rate DESC";
        $img_name = "down";
    }
    elseif($_GET["ord"] == 9)
    {
        $ord = " manager_email ASC";
        $img_name = "up";
        $ord_next_email = 10;
    }
    elseif($_GET["ord"] == 10)
    {
        $ord = " manager_email DESC";
        $img_name = "down";
    }
}
else
{
    $_GET["ord"] = 1;
    $ord = " unit_name ASC";
    $ord_next_name = 2;
    $img_name = "up";
}
if($ord != "")
{
    $ord = " Order By " . $ord;
}
?>
<table width="98%" height="350" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td colspan="3">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="body_head1">&nbsp;</td>
                    <td align="center" class="body_bg1"><strong>Manage Units</strong></td>
                    <td class="body_head2">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
        <td width="95%" align="center" valign="top">
            <table width="95%">
                <tr>
                    <td width="33%" height="17" valign="top" class="candylink">
                        <?php
                        if($_SESSION['IS_ADMIN'] == 1)
                            echo '<a href="add_unit.php" class="link2">[Add New Unit]</a>';
                        echo '<a href="update_unit_rates.php" class="link2">&nbsp;&nbsp;[Update Rates]</a>';
                        ?>
                    </td>
                    <td width="67%" align="right" valign="top" class="candylink"><span class="error">
      			      <?php
                      if(isset($_GET['opt']) || $op == 2)
                      {
                          if($op == '2')
                              echo "Unit Successfully Deleted";
                          elseif($_GET['opt'] == '1')
                              echo "Unit Successfully Created";
                          elseif($_GET['opt'] == '2')
                              echo "Unit Successfully Modified";
                          elseif($_GET['opt'] == '3')
                              echo "Unit Rates Successfully Updated";
                      }
                      ?>
      			    </span></td>
                </tr>
            </table>
            <form action="" method="post">
                <table width="95%" height="60">
                    <tr>
                        <td width="33%" valign="middle" class="heading">
                            Unit Name: <input type="text" name="s_unit_name" value="<?php echo $sun ?>" class="inptbox"
                                    size="30"/>&nbsp;&nbsp;
                            <input type="image" src="images/search.png" align="absmiddle" alt="Search" name="search"
                                    title="Search"/>
                        </td>
                    </tr>
                    <tr>
                        <td width="33%" valign="middle" class="heading">
                            Manager Email: <input type="text" name="s_unit_email" value="<?php echo $sue ?>"
                                    class="inptbox" size="100"/>&nbsp;&nbsp;
                            <input type="image" src="images/search.png" align="absmiddle" alt="Search" name="search1"
                                    title="Search"/>
                        </td>
                    </tr>
                </table>
            </form>
        </td>
        <td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
    </tr>
    <tr>
        <td width="1" bgcolor="#CAD6E9"><img src="images/bg.gif" width="1" height="1" alt=""></td>
        <td height="100%" width="100%" align="center" valign="top">
            <form action="" method="post" name="frm" id="frm">
                <table width="95%" border="1" cellpadding="0" cellspacing="0" bordercolor="#CCD5E6"
                        style="border-collapse :collapse;margin-top:10px;" align="center">
                    <tr>
                        <td>
                            <table width="100%" border="1" cellpadding="4" cellspacing="0" bordercolor="#CCD5E6"
                                    style="border-collapse :collapse" align="center">
                                <tr>
                                    <td width="15%" align="left" class="heading" style="padding-left:10px;">
                                        <a href="unit_listing.php?sun=<?php echo $sun ?>&sue=<?php echo $sue ?>&ord=<?php echo $ord_next_name ?>"
                                                class="link3">Unit Name</a>
                                        <?php
                                        if($_GET["ord"] == 1 || $_GET["ord"] == 2)
                                        {
                                            ?>
                                            &nbsp;    <a
                                                href="unit_listing.php?sun=<?php echo $sun ?>&sue=<?php echo $sue ?>&ord=<?php echo $ord_next_name ?>"
                                                class="link2"><img src="images/<?php echo $img_name ?>.gif" width="12"
                                                    height="11" alt="^" border="0"></a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                    <td width="10%" align="center" class="heading"><a
                                                href="unit_listing.php?sun=<?php echo $sun ?>&sue=<?php echo $sue ?>&ord=<?php echo $ord_next_basic ?>"
                                                class="link3">Basic Rate</a>
                                        <?php
                                        if($_GET["ord"] == 3 || $_GET["ord"] == 4)
                                        {
                                            ?>
                                            &nbsp;    <a
                                                href="unit_listing.php?sun=<?php echo $sun ?>&sue=<?php echo $sue ?>&ord=<?= $ord_next_basic ?>"
                                                class="link2"><img src="images/<?php echo $img_name ?>.gif" width="12"
                                                    height="11" alt="^" border="0"></a>
                                            <?php
                                        }
                                        ?>

                                    </td>
                                    <td width="10%" align="center" class="heading"><a
                                                href="unit_listing.php?sun=<?php echo $sun ?>&sue=<?php echo $sue ?>&ord=<?php echo $ord_next_peak ?>"
                                                class="link3">Peak Rate</a>
                                        <?php
                                        if($_GET["ord"] == 5 || $_GET["ord"] == 6)
                                        {
                                            ?>
                                            &nbsp;    <a
                                                href="unit_listing.php?sun=<?php echo $sun ?>&sue=<?php echo $sue ?>&ord=<?php echo $ord_next_peak ?>"
                                                class="link3"><img src="images/<?php echo $img_name ?>.gif" width="12"
                                                    height="11" alt="^" border="0"></a>
                                            <?php
                                        }
                                        ?>

                                    </td>
                                    <td width="10%" align="center" class="heading"><a
                                                href="unit_listing.php?sun=<?php echo $sun ?>&sue=<?php echo $sue ?>&ord=<?php echo $ord_next_week ?>"
                                                class="link3">Weekend Rate</a>
                                        <?php
                                        if($_GET["ord"] == 7 || $_GET["ord"] == 8)
                                        {
                                            ?>
                                            &nbsp;    <a
                                                href="unit_listing.php?sun=<?php echo $sun ?>&sue=<?php echo $sue ?>&ord=<?php echo $ord_next_week ?>"
                                                class="link3"><img src="images/<?php echo $img_name ?>.gif" width="12"
                                                    height="11" alt="^" border="0"></a>
                                            <?php
                                        }
                                        ?>

                                    </td>

                                    <td width="15%" align="center" class="heading"><a
                                                href="unit_listing.php?sun=<?php echo $sun ?>&sue=<?php echo $sue ?>&ord=<?php echo $ord_next_email ?>"
                                                class="link3">Manager&rsquo;s Email Address</a>
                                        <?php
                                        if($_GET["ord"] == 9 || $_GET["ord"] == 10)
                                        {
                                            ?>
                                            &nbsp;    <a
                                                href="unit_listing.php?sun=<?php echo $sun ?>&sue=<?php echo $sue ?>&ord=<?php echo $ord_next_email ?>"
                                                class="link2"><img src="images/<?php echo $img_name ?>.gif" width="12"
                                                    height="11" alt="^" border="0"></a>
                                            <?php
                                        }
                                        ?>
                                    </td>

                                    <td width="5%" align="center" class="heading">
                                        <input name="delete_all" title="Delete All Units" onclick="delete_chk_all()"
                                                type="checkbox"/>&nbsp;Delete
                                    </td>
                                </tr>
                                <?php
                                if(isset($_GET['page']) && $_GET['page'] != '')
                                    $page = $_GET['page'];
                                else
                                    $page = 1;

                                $qry = "Select * from cpefs_units Where 1  $sub $ord ";

                                $variables = getAdminPagingVariables($qry, $page, $qryParams, $qryParamTypes);

                                $toshow = $variables['toshow'];
                                $start = $variables['start'];
                                $totalPages = $variables['totalPages'];
                                $rows = $variables['rows'];
                                $qryParamsRefs = $variables['paramsRefs'];

                                if($rows > 0)
                                {
                                    $sql = $qry . " limit $start,$toshow";
                                    $res = $dbc->getResult($sql, __LINE__, __FILE__, $qryParamsRefs);
                                    $color = "bgc2";
                                    while($row = $res->fetch_array(MYSQLI_ASSOC))
                                    {
                                        if($color == "bgc")
                                            $color = "bgc2";
                                        else
                                            $color = "bgc";
                                        ?>
                                        <tr class="<?php echo $color ?>">
                                            <td align="left" style="padding-left:10px;"><a
                                                        name="<?php echo $row["unit_id"] ?>"></a>
                                                <a href="add_unit.php?mode=edit&id=<?php echo $row["unit_id"] ?>"
                                                        title="Edit <?php echo show_text($row["unit_name"]) ?>"
                                                        class="link2"><?php echo show_text($row["unit_name"]) ?></a>
                                            </td>
                                            <td align="right" style="padding-right:10px;">
                                                <?php echo $row["basic_rate"] ?>
                                            </td>
                                            <td align="right" style="padding-right:10px;">
                                                <?php echo $row["peak_rate"] ?>
                                            </td>
                                            <td align="right" style="padding-right:10px;">
                                                <?php echo $row["weekend_rate"] ?>
                                            </td>
                                            <td align="left" style="padding-left:10px;padding-right:10px;">
                                                <?php echo show_text($row["manager_email"]) ?>
                                            </td>
                                            <td align="center">
                                                <input type="hidden" name="id[]" value="<?php echo $row['unit_id'] ?>"/>
                                                <input name="delete[]" type="checkbox"
                                                        value="<?php echo $row['unit_id'] ?>"/>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else
                                {
                                    ?>
                                    <tr>
                                        <td align="center" colspan="7" valign="middle" class="error">
                                            <b>No Units Found!</b>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table>
                        </td>
                    </tr>
                </table>
                <br/>
                <table width="60%">
                    <tr align="center">
                        <td width="57%" height="10" align="right" valign="bottom">
                            <?php
                            if($_SESSION['IS_ADMIN'] == 1)
                                echo '<input name="submit" type="submit" class="button1" value="Submit" />';
                            ?>
                        </td>
                        <td width="43%" height="10" colspan="3" align="right" valign="bottom" class="link1">

                        </td>
                    </tr>
                </table>
            </form>
            <?php include "include/admin_footer.php"; ?>
