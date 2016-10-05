
                      <br />
              <?
              $content_sql = mysql_query("SELECT * FROM content");
              $content_row_ct = mysql_num_rows($content_sql);
              ?>
          
                <table cellspacing="5" cellpadding="5" class="altRows">
                <tr class="tdHdr"><td>Title</td><td>Actions</td></tr>
                <?
                while ($content = mysql_fetch_array($content_sql)) {
                ?>
                
                  <tr>
                    <td style="min-width: 220px;"><strong><?=$content['title'];?></strong></td>
                    <td><a href="a_page_edit.php?ID=<?=$content['ID'];?>" class="button"><i class="fa fa-edit"></i> Edit</a>  <a href="func.php?cmd=page_del&ID=<?=$content['ID'];?>" class="button" onClick="return confirm('Deleete <?=$content['name'];?>?');"><i class="fa fa-trash"></i> Delete</a></td>
                  </tr>
                
                <?
                }
                ?>
                </table>
              
              
              