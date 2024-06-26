import { Url } from 'url';
import { Config } from 'ziggy-js';

export interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

export interface PaginationMeta {
  current_page: number;
  last_page: number;
  per_page: number;
  total: number;
}

export interface PaginatedResponse<T> {
  data: T[];
  links: PaginationLink[];
  meta: PaginationMeta;
}

export interface Room {
  id: number;
  name: string;
  time_limit: number;
  tags: Tag[];
  created_at:string;
  user: User;
  users: User[];
  is_room_expired:boolean;
}

export interface Tag {
  id: number;
  name: string;
}

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    profile_img	:string;
    profile_text:string;
    followers: User[];
    following: User[];
}

export interface RoomComment {
    id: number;
    room_id: number;
    user_id: number;
    comment: string;
    user: User;
}

export interface Auth {
    user: User | null;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    title: string;
    auth: Auth;
    ziggy?: { location: string };
    room?: Room;
    rooms?: PaginatedResponse<Room>;
    tags?: Tag[]; 
    search?: string; 
    url:string;
};

export interface UserProfileProps {
  id: number;
  name: string;
  email: string;
  profile_img: string;
  profile_text: string;
}

export interface UserProfilePageProps extends PageProps {
  followingUsers: User[];
  followers: User[];
  user: User;
  isMyPage: boolean;
  userRooms: Room[];
  joinedRooms: Room[];
  totalRoomTime: number;
  followingUserNum: number;
  followedUserNum: number;
  authUserId: number | null;

}

export interface EndedTaskFormData {
  ended_task: string;
  room_id: number;
}