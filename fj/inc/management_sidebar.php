<?php
//---------------------------------------------------------
//  管理画面再度ナビゲーション
//---------------------------------------------------------
//
// 管理画面各ページへ遷移
//
//---------------------------------------------------------
?>
<nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">

            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="<?= Mg_Top ?>">
                  <span data-feather="file-text"></span>
                  案件管理
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?= Mg_UserList ?>">
                  <span data-feather="file-text"></span>
                  ユーザー管理
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="<?= Mg_AdminLIST ?>">
                  <span data-feather="file-text"></span>
                  管理者情報
                </a>
              </li>
            </ul>
          </div>
        </nav>