/**
 * Created by Libern on 26/7/16.
 */
export default function (value) {
    if (value && value.length > 0) {
        return value.replace(new RegExp('\r?\n', 'g'), '<br />');
    } else {
        return null;
    }
}
