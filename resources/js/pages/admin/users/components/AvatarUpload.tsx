import { useRef } from 'react';
import { Button } from '@/components/ui/button';
import AvatarDisplay from './AvatarDisplay';

type AvatarUploadProps = {
    avatarUrl: string | null;
    name: string;
    onChange: (file: File | null) => void;
    error?: string;
};

export default function AvatarUpload({ avatarUrl, name, onChange, error }: AvatarUploadProps) {
    const inputRef = useRef<HTMLInputElement>(null);

    function handleFileChange(e: React.ChangeEvent<HTMLInputElement>) {
        const file = e.target.files?.[0] ?? null;
        onChange(file);
    }

    function handleRemove() {
        onChange(null);
        if (inputRef.current) {
            inputRef.current.value = '';
        }
    }

    return (
        <div className="space-y-2">
            <div className="flex items-center gap-4">
                <AvatarDisplay avatarUrl={avatarUrl} name={name} />
                <div className="space-y-1">
                    <Button type="button" variant="outline" size="sm" onClick={() => inputRef.current?.click()}>
                        Choose Image
                    </Button>
                    {avatarUrl && (
                        <Button type="button" variant="ghost" size="sm" onClick={handleRemove}>
                            Remove
                        </Button>
                    )}
                    <p className="text-xs text-muted-foreground">JPG, PNG up to 2MB</p>
                </div>
            </div>
            <input
                ref={inputRef}
                type="file"
                accept="image/jpeg,image/png"
                className="hidden"
                onChange={handleFileChange}
            />
            {error && <p className="text-sm text-destructive">{error}</p>}
        </div>
    );
}
