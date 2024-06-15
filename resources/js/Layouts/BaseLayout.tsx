// resources/js/components/BaseLayout.tsx
import React, { ReactNode } from 'react';
import { usePage, Head } from '@inertiajs/react';
import PageTransition from '@/Components/PageTransition';
import Header from '@/Components/Header'; 
import { PageProps } from '@/types';

interface BaseLayoutProps {
  children: ReactNode;
  auth: any;
  ziggy: any;
}

const BaseLayout: React.FC<BaseLayoutProps> = ({ children, auth, ziggy }) => {
  const { url } = usePage<PageProps>().props;

  return (
    <div>
      <header>
        <Header auth={auth} ziggy={ziggy} />
      </header>
      <main>
        <PageTransition location={url}>
          {children}
        </PageTransition>
      </main>
      {/* <footer>
      </footer> */}
    </div>
  );
};

export default BaseLayout;
