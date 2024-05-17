// Header.tsx
import React from 'react';
import { Link, Head } from '@inertiajs/react';
import logo from '@/Pages/img/logo.svg';
import usersample from '@/Pages/img/user-sample.svg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons';
import { PageProps } from '@/types';
import Dropdown from '@/Components/Dropdown';



const Header: React.FC<PageProps> = ({ auth }) => {
  return (
    <header className="py-2 shadow-lg">
      <div className="max-w-screen-lg mx-auto flex items-center justify-between">
        <div className="flex items-center">
          <Link href={`/`}>
            <img src={logo} className="header-logo" alt="TASKLIVE" />
          </Link>
        </div>
        <ul className="flex items-center space-x-4">
          <li className="flex items-center">
            <form action="index.php" method="GET" className="">
              <div className="divided">
                <span className="lg:hidden">検索</span>
                <label htmlFor="">
                  {/* <input type="text" name="search" className="input search-input" value="" /> */}
                </label>
              </div>
            </form>
            <FontAwesomeIcon icon={faMagnifyingGlass} className="ml-2" size='lg' color='#7a7a7a' />
          </li>
          <li className=''>
            <nav className="flex items-center space-x-4">
              <Link
                href={route('room.create')}
                className="text-sky-500 font-semibold"
              >
                部屋を作る
              </Link>
              {auth.user ? (
                        <div className="hidden sm:flex sm:items-center sm:ms-6">
                          <div className="ms-3 relative">
                              <Dropdown>
                                  <Dropdown.Trigger>
                                      <span className="inline-flex rounded-md">
                                          <button
                                              type="button"
                                              className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                          >
                                              {/* {auth.user?.name || '名称未設定'} */}
                                              <div className="user-info">
                                                <img src={auth.user?.profile_img || usersample} alt={auth.user?.name}  className="rounded-full object-cover w-12 h-12" />
                                                <span className="user-name"></span>
                                              </div>
                                          </button>
                                      </span>
                                  </Dropdown.Trigger>

                                  <Dropdown.Content>
                                      <Dropdown.Link href={route('user.mypage')}>プロフィール</Dropdown.Link>
                                      <Dropdown.Link href={route('logout')} method="post" as="button">
                                          ログアウト
                                      </Dropdown.Link>
                                  </Dropdown.Content>
                              </Dropdown>
                          </div>
                        </div>
              ) : (
                  <>
                      <Link
                          href={route('login')}
                          className="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                      >
                          ログイン
                      </Link>
                      <Link
                          href={route('register')}
                          className="text-slate-500 font-semibold border border-slate-500 px-2 py-2 rounded"
                      >
                          新規登録
                      </Link>
                  </>
              )}
              </nav>
          </li>
          {/* <?php if (!empty($_SESSION['login_limit'])) { ?>
            <?php if (!isLogin()) { ?>
              <li className="nav-list">
                <a href="login.php">ログイン</a>
              </li>
              <li className="nav-list">
                <a href="signup.php" className="btn btn-signup">新規登録</a>
              </li>
              <li className="nav-list">
                <a href="makeRoom.php" className="nav-active btn-make-room ph-btn-make-room">
                  <i className="fas fa-pen-square fa-2x"></i>
                </a>
              </li>
            <?php } else { ?>
              <li className="nav-list nav-list-menu">
                <span className="js-toggle-header-link">
                  <img src="<?php echo sanitize($userImg); ?>" alt="ユーザー画像" className="user-img">
                </span>
                <div className="js-show-header-link header-link-container header-link-container-pc d-sm-none">
                  <div className="header-links">
                    <ul className="header-links">
                      <li className="header-link">
                        <a href="userpage.php">
                          マイページ
                        </a>
                      </li>
                      <li className="header-link">
                        <a href="logout.php">
                          ログアウト
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </li>
              <li className="nav-list">
                <a href="makeRoom.php" className="nav-active btn-make-room ph-btn-make-room"><i className="fas fa-pen-square fa-2x"></i></a>
              </li>
            <?php } ?>
          <?php } else { ?>
            <li className="nav-list">
              <span className="js-toggle-login-link toggle-login-link-ph">
                <img src="img/user-sample.svg" alt="ユーザー画像" className="user-img">
              </span>
            </li>
            <li className="nav-list d-sm-none">
              <a href="login.php">ログイン</a>
            </li>
            <li className="nav-list d-sm-none">
              <a href="signup.php" className="btn btn-signup">新規登録</a>
            </li>
            <li className="nav-list d-lg-none">
              <a href="makeRoom.php" className="nav-active btn-make-room ph-btn-make-room"><i class="fas fa-pen-square fa-2x"></i>
              </a>
            </li>
          <?php } ?> */}
        </ul>
      </div>
  </header>
  );
};

export default Header;