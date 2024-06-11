// src/Components/Pagination.tsx

import React from 'react';
import { Link } from '@inertiajs/react';
import { PaginationLink } from '@/types';

interface PaginationProps {
    links: PaginationLink[];
}

const Pagination: React.FC<PaginationProps> = ({ links }) => {
    // ページ番号のみのリンクをフィルタリング（「前へ」と「次へ」を削除）
    const pageLinks = links.filter(link => !isNaN(Number(link.label)));

    return (
        <nav className='mx-auto mt-10'>
            <ul className="flex">
                {pageLinks.map((link, index) => (
                    <li
                        key={index}
                        className='mx-1'
                    >
                        {link.active ? (
                            <span className="py-2 px-3 bg-slate-600 text-white cursor-default hover:bg-slate-600 hover:text-slate-300 transition duration-300">
                                {link.label}
                            </span>
                        ) : (
                            <Link className="py-2 px-3 bg-slate-100 text-black hover:bg-slate-600 hover:text-slate-300 transition duration-300" href={link.url!}>
                                {link.label}
                            </Link>
                        )}
                    </li>
                ))}
            </ul>
        </nav>
    );
};

export default Pagination;
