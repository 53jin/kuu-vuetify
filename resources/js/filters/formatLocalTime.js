/**
 * Created by Libern on 30/5/18.
 */
export default function (value) {
    if (value && value.length > 0) {
        return dayjs.utc(value).local().format('YYYY-MM-DD HH:mm:ss');
    } else {
        return null;
    }
}
