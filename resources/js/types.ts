// types.ts
export interface User {
    id: number;
    name: string;
    email: string;
    role: string;
}

export interface Role {
    name: string;
}

export interface FlashMessages {
    success?: string;
    error?: string;
}

export interface PageProps {
    users: User[];
    roles: Role[];
    flash: FlashMessages;
}
