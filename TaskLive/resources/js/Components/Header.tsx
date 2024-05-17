import React, { useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import logo from '@/Pages/img/logo.svg';
import usersample from '@/Pages/img/user-sample.svg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faMagnifyingGlass } from '@fortawesome/free-solid-svg-icons';
import { PageProps } from '@/types';
import Dropdown from '@/Components/Dropdown';

interface HeaderProps extends PageProps {}

const Header: React.FC<HeaderProps> = ({ auth }) => {
  const { data, setData, get } = useForm({ search: '' });

  const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const query = e.target.value;
    setData('search', query);
  };

  const handleSearchSubmit = (e: React.FormEvent<HTMLFormElement> | React.MouseEvent<HTMLButtonElement, MouseEvent>) => {
    e.preventDefault();
    if (data.search) {
      get('/', { replace: true });
    }
  };

  const handleKeyPress = (e: React.KeyboardEvent<HTMLInputElement>) => {
    if ( e.key === 'Enter') {
      e.preventDefault();
      handleSearchSubmit(e as unknown as React.FormEvent<HTMLFormElement>);
    }
  };

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
            <form className="divided flex items-center" onSubmit={handleSearchSubmit}>
              <span className="lg:hidden">検索</span>
              <label htmlFor="search">
                <input
                  type="text"
                  name="search"
                  className="input search-input"
                  onChange={handleSearchChange}
                  onKeyPress={handleKeyPress}
                />
              </label>
              <button type="submit">
                <FontAwesomeIcon icon={faMagnifyingGlass} className="ml-2" size="lg" color="#7a7a7a" />
              </button>
            </form>
          </li>
          <li>
            <nav className="flex items-center space-x-4">
              <Link href={route('room.create')} className="text-sky-500 font-semibold">
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
                            <div className="user-info">
                              <img
                                src={auth.user?.profile_img || usersample}
                                alt={auth.user?.name}
                                className="rounded-full object-cover w-12 h-12"
                              />
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
                  <Link href={route('register')} className="text-slate-500 font-semibold border border-slate-500 px-2 py-2 rounded">
                    新規登録
                  </Link>
                </>
              )}
            </nav>
          </li>
        </ul>
      </div>
    </header>
  );
};

export default Header;
