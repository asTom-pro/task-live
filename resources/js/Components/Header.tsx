// Header.tsx
import React, {useState, useEffect} from 'react';
import { Link, useForm } from '@inertiajs/react';
import logo from '@/Pages/img/logo.svg';
import usersample from '@/Pages/img/user-sample.svg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faMagnifyingGlass, faUser, faPlusCircle } from '@fortawesome/free-solid-svg-icons';
import { Auth } from '@/types';
import Dropdown from '@/Components/Dropdown';

interface HeaderProps {
  auth: Auth;
  ziggy?: { location: string };
}

const Header: React.FC<HeaderProps> = ({ auth, ziggy }) => {

  const { data, setData, get } = useForm({ search: '' });
  const [isSearchBoxVisible, setIsSearchBoxVisible] = useState<boolean>(false);

  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const searchQuery = params.get('search');
    if (searchQuery) {
      setData('search', searchQuery);
    }
  }, []);

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
    if (e.key === 'Enter') {
      e.preventDefault();
      handleSearchSubmit(e as unknown as React.FormEvent<HTMLFormElement>);
    }
  };

  const handleSeachBoxToggle = (e: React.MouseEvent<HTMLButtonElement>) => {
    e.preventDefault();
    setIsSearchBoxVisible((prevState: boolean) => !prevState);
  }

  return (
    <>
      <header className="py-2 px-6 lg:px-24">
        <div className="mx-auto flex items-center justify-between">
          <div className="flex items-center">
            <Link href={`/`}>
              <img src={logo} className="header-logo" alt="TASKLIVE" />
            </Link>
          </div>
          <ul className="flex items-center space-x-4">
            <li className="flex items-center">
              <form className="hidden lg:flex items-center" onSubmit={handleSearchSubmit}>
                <label htmlFor="search">
                  <input
                    type="text"
                    name="search"
                    className="hidden lg:inline-block input search-input"
                    onChange={handleSearchChange}
                    onKeyDown={handleKeyPress}
                    value={data.search}
                  />
                </label>
                <button type="submit">
                  <FontAwesomeIcon icon={faMagnifyingGlass} className="ml-2" size="lg" color="#7a7a7a" />
                </button>
              </form>
            </li>
            <li>
              <nav className="flex items-center space-x-4">
                <button onClick={handleSeachBoxToggle}>
                  <FontAwesomeIcon icon={faMagnifyingGlass} className="inline-block lg:hidden ml-2" size="lg" color="#7a7a7a" />
                </button>
                <Link href={route('room.create')} className="hidden lg:inline-block text-sky-500 font-semibold">
                  部屋を作る
                </Link>
                <Link href={route('room.create')} className="lg:hidden text-sky-500 font-semibold">
                  <FontAwesomeIcon icon={faPlusCircle} className="ml-2 text-sky-500" size="2x" />
                </Link>
                {auth.user ? (
                  <div className="sm:flex sm:items-center sm:ms-6">
                    <div className="relative">
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
                      className="hidden lg:inline-block rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                      ログイン
                    </Link>
                    <Link href={route('register')} className="hidden lg:inline-block text-slate-500 font-semibold border border-slate-500 px-2 py-2 rounded">
                      新規登録
                    </Link>
                    <Link href={route('login')} className=" lg:hidden text-slate-500 px-2 py-2 rounded">
                      <FontAwesomeIcon icon={faUser} className="text-slate-500" size="lg" />
                    </Link>
                  </>
                )}
              </nav>
            </li>
          </ul>
        </div>
      </header>
      {isSearchBoxVisible && (
      <div className='py-5 px-6 lg:px-24 lg:hidden'>
        <div>
          <form className="w-full flex" onSubmit={handleSearchSubmit}>
            <input
              type="text"
              name="search"
              placeholder='キーワードで検索する'
              className="w-full input search-input bg-slate-100"
              onChange={handleSearchChange}
              onKeyDown={handleKeyPress}
            />
            <button type="submit" className="border-y border-r border-slate-500 flex items-center justify-center py-2 px-3 rounded-s-lg transition" dir="rtl">
              <FontAwesomeIcon icon={faMagnifyingGlass} size="lg" style={{ color: '#7a7a7a' }} />
            </button>
          </form>
        </div>
      </div>
      )}
    </>
  );
};

export default Header;
