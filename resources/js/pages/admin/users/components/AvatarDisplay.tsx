import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';

type AvatarUploadProps = {
    avatarUrl: string | null;
    name: string;
    size?: 'sm' | 'lg';
};

function getInitials(name: string): string {
    return name
        .split(' ')
        .map((part) => part[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
}

export default function AvatarDisplay({ avatarUrl, name, size = 'lg' }: AvatarUploadProps) {
    const sizeClass = size === 'lg' ? 'size-16' : 'size-8';
    const fallbackSize = size === 'lg' ? 'text-lg' : 'text-xs';

    return (
        <Avatar className={sizeClass}>
            <AvatarImage src={avatarUrl ?? undefined} alt={name} />
            <AvatarFallback className={fallbackSize}>{getInitials(name)}</AvatarFallback>
        </Avatar>
    );
}
