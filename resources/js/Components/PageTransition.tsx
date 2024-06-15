import React from 'react';
import { motion } from 'framer-motion';

type PageTransitionProps = {
    location: string;
    children: React.ReactNode;
};

const PageTransition: React.FC<PageTransitionProps> = ({ location, children }) => {
    return (
        <motion.div
            key={location}
            initial={{ opacity: 0.3 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.3 }}
        >
            {children}
        </motion.div>
    );
};

export default PageTransition;
