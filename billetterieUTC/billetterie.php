<?php
  include "ressources/php/include.php";

  $toOpenAt = 1515006000;
  $totalToSell = 1480;
  $isOpen = $toOpenAt < time();

  if (isset($_SESSION['id'])) {
    removeOldTickets();

    $query = $db->request(
      "SELECT * FROM tickets, types WHERE idUser = ? AND tickets.idType = types.id",
      array($_SESSION['id'])
    );

    $ticketsReserved = $query->fetchAll();

    foreach ($ticketsReserved as $ticket) {
      if ($ticket['status'] == 0) {
        header('Location: ./account.php');
        exit;
      }
    }
  }

  $query = $db->request(
    "SELECT count(id) AS nbr FROM tickets",
    array()
  );

  $data = $query->fetch();
  $allTickets = $data['nbr'];

  $query = $db->request(
    "SELECT * FROM types ORDER BY priority DESC, sellToStudentsOnly DESC, sellToTremplinOnly DESC, sellToContributers DESC",
    array()
  );

  $types = $query->fetchAll();
  $priority = 0;

  if ($isOpen && $allTickets < $totalToSell && isset($_SESSION['id']) && isset($_POST['accept']) && $_POST['accept'] == 'on' && isset($_POST['tickets'])) {
    foreach ($types as $type) {
      if (!isset($_POST['tickets'][$type['id']]))
        continue;

      if ($type['sellToStudentsOnly'] && (!isset($_SESSION['id']) || $_SESSION['login'] == NULL))
        continue;

      if ($type['sellToStudentsOnly'] && ($type['sellToContributers'] != $_SESSION['isContributer']))
        continue;

      if ($type['sellToTremplinOnly'] && !$_SESSION['isTremplin'])
        continue;

      if ($type['priority'] > $priority) {
        if ($type['nbrToSell'] <= 0)
          continue;

        $priority = $type['priority'];
      }

      if ($type['priority'] < $priority)
        continue;

      $nbrReserved = 0;
      $nbrReservedForContributers = 0;
      if (isset($_SESSION['id'])) {
        foreach ($ticketsReserved as $ticketReserved) {
          $nbrReserved += $ticketReserved['idType'] == $type['id'];
          $nbrReservedForContributers += $ticketReserved['sellToContributers'];
        }
      }
      $nbrReserved /= $type['nbrInPack'];
      $nbrReservedForContributers /= $type['nbrInPack'];

      if ($type['sellToContributers'] && $nbrReservedForContributers >= 1)
        continue;

      $max = min($type['nbrPerPerson'] - $nbrReserved, $type['nbrToSell']);

      if ($_POST['tickets'][$type['id']] > $max || $_POST['tickets'][$type['id']] < 1)
        continue;

      if($type['id']== 1 || $type['id']== 2 || $type['id']== 3)
      {
        $db->request(
          'UPDATE types SET nbrToSell = nbrToSell - ? WHERE id = 1 or id=2 or id=3',
          array($_POST['tickets'][$type['id']])
        );
      }

      if($type['id']== 4 || $type['id']== 5 || $type['id']== 6)
      {
        $db->request(
          'UPDATE types SET nbrToSell = nbrToSell - ? WHERE id = 4 or id=5 or id=6',
          array($_POST['tickets'][$type['id']])
        );
      }

      if($type['id']== 7 || $type['id']== 8 || $type['id']== 9 )
      {
        $db->request(
          'UPDATE types SET nbrToSell = nbrToSell - ? WHERE id = 7 or id=8 or id=9',
          array($_POST['tickets'][$type['id']])
        );
      }

      if($type['id']== 10 || $type['id']== 11 || $type['id']== 12 )
      {
        $db->request(
          'UPDATE types SET nbrToSell = nbrToSell - ? WHERE id = 10 or id=11 or id=12',
          array($_POST['tickets'][$type['id']])
        );
      }

      if($type['id']== 13 || $type['id']== 14 || $type['id']== 15 )
      {
        $db->request(
          'UPDATE types SET nbrToSell = nbrToSell - ? WHERE id = 13 or id=14 or id=15',
          array($_POST['tickets'][$type['id']])
        );
      }





      $request = $db->prepare('INSERT INTO tickets VALUES(NULL, ?, ?, NULL, ?, ?, ?, 0, ?, ?, NULL)');

      if (isset($_SESSION['login']) && ($type['sellToStudentsOnly'] || $type['sellToContributers']))
        $db->execute($request, array($_SESSION['id'], $type['id'], $_SESSION['lastname'], $_SESSION['firstname'], NULL, 0, time()));
      else {
        for ($i = 0; $i < $_POST['tickets'][$type['id']] * $type['nbrInPack']; $i++)
          $db->execute($request, array($_SESSION['id'], $type['id'], NULL, NULL, NULL, 0, time()));
      }
    }
/* Permettait de ne pas passer par PayUTC si le total vallait 0€
    $query = $db->request(
      "SELECT sum(price) AS total FROM tickets, types WHERE idUser = ? AND tickets.idType = types.id AND status = 0",
      array($_SESSION['id'])
    );

    $data = $query->fetch();

    if ($data['total'] == 0) {
      $query = $db->request(
        "UPDATE tickets SET status = 1 WHERE idUser = ? AND status = 0",
        array($_SESSION['id'])
      );
    }
*/
    generateTransaction();

    header('Location: ./account.php');
    exit;
  }

  include "ressources/php/header.php";
?>

<div id="bandeau_sell">
  <form name="form_connection" method="post" action='./billetterie.php' style="text-align:center" onsubmit="return <?php echo (!isset($_SESSION['id'])) ? 'needToBeConnected()' : 'validate()'; ?>">
    <h3>
      Billetterie <?php
        if (!$isOpen) {
          ?>
            ouverte dans <span id='hourToOpen'><?php echo floor(($toOpenAt - time()) / (60 * 60)); ?></span> h <span id='minToOpen'><?php echo floor(($toOpenAt - time()) / 60 % 60); ?></span> m <span id='secToOpen'><?php echo ceil(($toOpenAt - time()) % 60); ?></span> s</span>
          <?php
        }
      ?>
    </h3>

    <div id="tickets">


      <?php
        $seeInfos = FALSE;
        $putToOne = TRUE;
        $mySpace =0;
        echo '<div>';
        echo   '<div class="tickets">';

        foreach ($types as $type) {

          if(!($mySpace%3))
          {
            echo '</div>';
            echo '<div class="Title">',$type['name'],': </div>';
            echo   '<div class="tickets">';
          }


          if ($type['sellToStudentsOnly'] && (!isset($_SESSION['id']) || $_SESSION['login'] == NULL))
            continue;

          if ($type['sellToStudentsOnly'] && ($type['sellToContributers'] != $_SESSION['isContributer']))
            continue;

          if ($type['sellToTremplinOnly'] && (!isset($_SESSION['isTremplin']) || !$_SESSION['isTremplin']))
            continue;

          $nbrReserved = 0;
          $nbrReservedForContributers = 0;
          if (isset($_SESSION['id'])) {
            foreach ($ticketsReserved as $ticketReserved) {
              $nbrReserved += $ticketReserved['idType'] == $type['id'];
              $nbrReservedForContributers += $ticketReserved['sellToContributers'];
            }
          }
          $nbrReserved /= $type['nbrInPack'];
          $nbrReservedForContributers /= $type['nbrInPack'];

          if ($type['sellToContributers'] && $nbrReservedForContributers >= $type['nbrPerPerson'])
            continue;

          $max = min($type['nbrPerPerson'] - $nbrReserved, $type['nbrToSell']);

          if ($type['priority'] > $priority) {
            if ($max <= 0)
              continue;

            $priority = $type['priority'];
          }

          if ($type['priority'] < $priority)
            continue;

          if (strpos($type['info'], '*'))
            $seeInfos = TRUE;

          ?>
          <div class="ticket">
            <div class="ticket_container">
              <!--<img src="./ressources/img/fond_place.png" alt="photo ici"></img>-->

              <div class="ticket_name">
                <img src="./ressources/img/carte_droite.png" alt="photo ici"></img>
                <div class="information">
                <h3><?php echo $type['name']; ?></h3>
                <h4><?php echo $type['info']; ?></h4>
                </div>

                </div>
              <div class="ticket_information">
                <img src="./ressources/img/carte_droite.png" alt="photo ici"></img>
                <h2><?php echo ($type['price'] == 0) ? 'Gratuit' : $type['price'].' €'; ?></h2>
              </div>
              </div>

              <div class="ticket_nbr">
                <?php
                  if (!$isOpen)
                    echo 'Billetterie non ouverte';
                  elseif ($type['nbrPerPerson'] - $nbrReserved <= 0)
                    echo 'Vous ne pouvez plus en réserver';
                  else {
                ?>
                Nombre de place(s):
                  <select name="tickets[<?php echo $type['id']; ?>]" max='<?php echo $max, "' ", (!isset($_SESSION['id']) || $type['nbrPerPerson'] - $nbrReserved <= 0) ? 'onClick="needToBeConnected(); $(this).val(0)"' : 'onChange="changeTotal();" onKeyUp="changeTotal();" onClick="changeTotal();"'; ?>>
                    <?php
                      for ($i = 0; $i <= $max; $i++)
                        echo '<option value="'.$i.'">'.$i.'</option>';
                      ?>
                  </select>
                <?php } ?>
              </div>

          </div>

          <?php
          $mySpace++;

        }
      ?>

        </div>




    <?php
      if ($seeInfos) {
        ?>
        <h5 style="color:#337ab7">* justificatif requis</h5>
        <?php
      }
    ?>
      <div class='validation'>
      <input class="button form-button" onClick="removeAll()" value="VIDER MON PANIER" style="border-radius: 5px" type='button'>
      <br />
      <br />
      <div>
    <?php
      if ($totalToSell <= $allTickets)
        echo "Il n'est plus possible d'acheter de billets";
      else if ($isOpen) {
        ?>
          <label for="accept"><input name="accept" id="accept" type='checkbox' required='required' style="margin-right: 5px" />En cochant cette case, j'ai lu et j'accepte les <a href=./ressources/pdf/conditions.pdf>conditions générales de vente</a></label><br />
          <input id='submitPrice' class="button form-button" value="RESERVER ET PAYER" style="border-radius: 5px" type="submit">
        <?php
      }
      elseif (!isset($_SESSION['id']))
        echo 'Pensez à vous connecter pour réserver vos places';
    ?>

  </div>
</div>
  </form>
</div>

<script>
  validate = function () {
    text = ''

    $('.ticket').each(function(i, obj) {
        if ($(obj).find('select').length == 0 || $($(obj).find('select')[0]).val() == 0)
          return;

        text += '\n\
        - ' + $($(obj).find('select')[0]).val() + 'x ' + $($(obj).find('h3')[0]).text();
    });

    if (text == '') {
      alert('Veuillez au moins sélectionner une place à réserver');
      return false;
    }

    total = getTotal();

    return confirm(total > 0 ? "Voulez-vous vraiment réserver et payer pour " + total + " €:" + text + "\n\
    \n\
    Vous serez invité(e) par la suite à payer votre/vos billet(s)." : "Voulez-vous vraiment réserver: " + text + "\n\
    \n\
    Votre billet sera automatiquement validé.");
  };

  needToBeConnected = function () {
    alert('Pour prendre une place, il est nécessaire d\'avoir un compte')
    return false;
  };

  addTicket = function (obj) {
    if (parseInt($($(obj).find('select')[0]).val()) < parseInt($($(obj).find('select')[0]).attr('max')))
      $($(obj).find('select')[0]).val(parseInt($($(obj).find('select')[0]).val()) + 1)
  }

  removeAll = function () {
    $('.ticket select').each(function(i, obj) {
      $(obj).val(0);
    });

    changeTotal();
  };

  getTotal = function () {
    total = 0;
    reduction = 0;

    $('.ticket').each(function(i, obj) {
        if ($(obj).find('select').length == 0 || $($(obj).find('select')[0]).val() == 0 || $($(obj).find('h2')[0]).text() === 'Gratuit')
          return;
        reduction+= ($($(obj).find('select')[0]).val() * 1);
        total += ($($(obj).find('select')[0]).val() * $($(obj).find('h2')[0]).text().split(' ')[0]);


    });

    if(reduction%3==0 )
    {
      nb = reduction/3;
      total -=2*nb;
    }

        if(reduction%4==0 )
        {
          nb = reduction/4;
          total -=2*nb;
        }
    if(reduction%5==0)
    {
      nb = reduction/5;
      total -=5*nb;
    }

    return total;
  };

  changeTotal = function () {
    total = getTotal();

    $('#submitPrice').val('RESERVER ET PAYER' + (total > 0 ? (' ' + total + ' €') : ''));
  };

  $($('select')[0]).val(1);
  changeTotal();

  <?php
    if (!$isOpen) {
      ?>
        hour = $('#hourToOpen');
        min = $('#minToOpen');
        sec = $('#secToOpen');

        setInterval(function () {
          if (hour.text() <= 0 && min.text() <= 0 && sec.text() <= 0)
            window.location.href = './billetterie.php';
          else if (sec.text() <= 0) {
            sec.text(59);

            if (min.text() <= 0) {
              min.text(59);
              hour.text(hour.text() - 1);
            }
            else
              min.text(min.text() - 1);
          }
          else
            sec.text(sec.text() - 1);
        }, 1000);
      <?php
    }
  ?>
</script>

<?php
  echo '</div>';
  include "ressources/php/footer.php";
?>