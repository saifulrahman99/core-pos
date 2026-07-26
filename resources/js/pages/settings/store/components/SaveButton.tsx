import { Button } from '@/components/ui/button';

export default function SaveButton({ processing }: { processing: boolean }) {
    return (
        <div className="flex items-center gap-4 pt-4">
            <Button
                type="submit"
                disabled={processing}
                data-test="update-store-button"
            >
                Save
            </Button>
        </div>
    );
}
