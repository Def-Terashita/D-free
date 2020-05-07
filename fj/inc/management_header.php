<?php

//---------------------------------------------------------
//  管理画面ヘッダーHTML
//---------------------------------------------------------
//
// D-freeクリックでTOPへ遷移
// SignOutクリックでログアウト（management_signout.php）
//
//---------------------------------------------------------

?>
    <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="<?= Mg_Top ?>">D-free</a>
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="<?= Mg_SignOut ?>">Sign out</a>
        </li>
      </ul>
    </nav>