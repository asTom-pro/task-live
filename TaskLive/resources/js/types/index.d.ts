import { Config } from 'ziggy-js';

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Auth {
    user: User | null;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: Auth;
    ziggy?: { location: string };
  };
