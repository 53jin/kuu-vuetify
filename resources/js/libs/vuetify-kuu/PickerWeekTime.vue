<template>
    <table
        class="scheduler"
        style="width: 100%;
        table-layout: fixed;
        border-collapse: collapse;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;"
    >
        <thead style="border: 1px solid black;">
        <tr
            style="border: 1px solid black;">
            <td style="width: 80px;"></td>
            <td
                style="border: 1px solid black;
                    text-align: center;"
                v-for="n in 24"
                :key="n"
                class="scheduler-hour"
                :colspan="accuracy"
                @click="handleClickHour(n-1)"
            >
                {{ n - 1 }}
            </td>
        </tr>
        </thead>
        <tbody>
        <tr
            style="border: 1px solid black;"
            v-for="day in 7"
            :key="day"
        >
            <td
                style="border: 1px solid black;
                    text-align: center;"
                class="scheduler-day-toggle"
                @click="handleClickDay(day)"
            >
                {{ weekDay[day - 1] }}
            </td>

            <td
                style="border: 1px solid black;
                    text-align: center;"
                v-for="hourIndex in cellColAmout"
                :key="hourIndex"
                class="scheduler-hour"
                :style="{'background-color': isCellSelected(day, hourIndex - 1)}"
                @mousedown="handleMouseDown(day, hourIndex - 1)"
                @mousemove="handleMouseMove(day, hourIndex - 1)"
                @mouseup="handleMouseUp(day, hourIndex - 1)"
            />
        </tr>
        <tr>
            <td colspan="25" style="height: 11px;line-height:8px">&nbsp;</td>
        </tr>
        <tr>

            <td style="border: 1px solid black;text-align: center;">Day</td>
            <td
                style="border: 1px solid black;text-align: center;"
                v-for="n in 24"
                :key="n"
                class="scheduler-hour"
                :style="{'background-color': isDaySelected(n-1)}"
                @mousedown="handleDownHour(n-1)"
                @mousemove="handleMoveHour(n-1)"
                @mouseup="handleUpHour(n-1)"
            >
            </td>
        </tr>
        </tbody>
        <tfoot>

        </tfoot>
    </table>
</template>

<script>
    export default {

        name: 'Scheduler',
        props: {
            value: [Array],
            accuracy: {
                type: Number,
                default: 1
            },
            multiple: Boolean,
            disabled: Boolean
        },

        data() {
            return {
                selectModes: {
                    JOIN: 1, // 合并模式，添加到选区
                    MINUS: 2, // 减去模式，从之前的选区中减去
                    REPLACE: 3, // 替换模式，弃用之前的选区，直接使用给定的选区作为最终值（该模式用于单选情况 multiple = false）
                    NONE: 0
                },
                selectMode: 0,
                startCoord: null,
                endCoord: null,
                selected: {},
                tempSelected: null,
                moving: false,
                weekDay: ['Sun','Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            }
        },

        computed: {
            cellColAmout() {
                return this.accuracy * 24
            },
        },

        watch: {
            value: {
                handler(val) {
                    val = this.setWeekTime(val)

                    this.selected = val;
                    this.tempSelected = val;
                },
                immediate: true
            }
        },

        methods: {
            makeRange(from, to) {
                if (from > to) {
                    from = from + to
                    to = from - to
                    from = from - to
                }

                let res = []
                for (let i = from; i <= to; i++) {
                    res.push(i)
                }
                return res
            },
            makeMatrix(startCoord, endCoord) {
                let matrix = {}
                let colArr = this.makeRange(startCoord[1], endCoord[1])
                let fromRow = startCoord[0] < endCoord[0] ? startCoord[0] : endCoord[0]
                let steps = Math.abs(startCoord[0] - endCoord[0]) + 1
                for (let i = 0; i < steps; i++) {
                    matrix[fromRow + i] = colArr.slice(0)
                }
                return matrix
            },
            mergeArray(origin, addition) {
                let hash = {};
                let res = [];

                origin.forEach(function (item, i) {
                    hash[item] = 1;
                    res[i] = item
                });

                addition.forEach(function (item) {
                    if (!hash[item]) {
                        res.push(item)
                    }
                });

                return res.sort(function (num1, num2) {
                    return num1 - num2
                })
            },
            rejectArray(origin, reject) {
                let hash = {}
                let res = []

                reject.forEach(function (item, i) {
                    hash[item] = i
                });

                origin.forEach(function (item) {
                    if (!hash.hasOwnProperty(item)) {
                        res.push(item)
                    }
                });

                return res.sort(function (num1, num2) {
                    return num1 - num2
                });
            },
            sortCoord(num1, num2) {
                if (num1 > num2) {
                    return [num2, num1]
                }
                return [num1, num2]
            },
            ////////////////
            isCellSelected(day, hourIndex) {
                const {tempSelected = {}} = this;
                const selectedHours = tempSelected[day]
                if (selectedHours && ~selectedHours.indexOf(hourIndex)) {
                    return '#1976d2'
                } else {
                    return ''
                }
            },
            isDaySelected(hour) {
                const {tempSelected = {}} = this;
                let num = 0;
                for(let i in tempSelected){
                    for(let k in tempSelected[i]){
                        if(tempSelected[i][k] == hour){
                            num++;
                        }
                    }
                }
                if (num == 7 ) {
                    return '#1976d2'
                } else {
                    return ''
                }
            },
            handleDownHour(hour) {
                this.moving = true;
                this.startCoord = [1, hour];
                this.endCoord = [7, hour];
                this.selectMode = this.getCellSelectMode(this.startCoord);
                this.updateRange(this.startCoord, this.endCoord, this.selectMode);
            },

            handleMoveHour(hour) {
                if (!this.moving) {
                    return false
                }
                this.endCoord = [7, hour];
                this.updateRange(this.startCoord, this.endCoord, this.selectMode)
            },
            handleUpHour(hour) {
                if (!this.moving) {
                    return false
                }
                this.end();
            },
            handleClickHour(hour) {
                if (this.disabled) {
                    return
                }
                const fromIndex = hour * this.accuracy
                const toIndex = fromIndex + this.accuracy - 1;
                const startCoord = [1, fromIndex];
                const endCoord = [7, toIndex];
                const selectMode = this.getRangeSelectMode(startCoord, endCoord);
                this.updateToggle(startCoord, endCoord, selectMode);
            },
            handleClickDay(day) {
                if (this.disabled) {
                    return
                }
                const startCoord = [day, 0];
                const endCoord = [day, 24 * this.accuracy - 1];
                const selectMode = this.getRangeSelectMode(startCoord, endCoord);
                this.updateToggle(startCoord, endCoord, selectMode);
            },
            handleMouseDown(row, col) {
                if (this.disabled) {
                    return
                }
                this.moving = true;
                this.startCoord = [row, col];
                this.endCoord = this.startCoord.slice(0);
                this.selectMode = this.getCellSelectMode(this.startCoord);
                this.updateRange(this.startCoord, this.endCoord, this.selectMode);
            },
            handleMouseMove(row, col) {
                if (!this.moving) {
                    return false
                }
                if (!this.selectMode || !this.startCoord || (this.endCoord &&
                    this.endCoord[0] === row &&
                    this.endCoord[1] === col)
                ) {
                    return false
                }
                this.endCoord = [row, col];
                this.updateRange(this.startCoord, this.endCoord, this.selectMode)
            },
            handleMouseUp(row, col) {
                if (!this.moving) {
                    return false
                }
                if (this.startCoord[0] === this.endCoord[0] &&
                    this.startCoord[1] === this.endCoord[1]) {
                    //this.updateRange(this.startCoord, this.endCoord, this.selectMode)
                }
                this.end()
            },
            merge(origin, current, selectMode) {
                let res = {};
                let i, m;
                if (selectMode === this.selectModes.REPLACE) {
                    for (i = 1; i <= 7; i++) {
                        if (current[i] && current[i].length) {
                            res[i] = current[i].slice(0)
                        }
                    }
                    return res
                }
                for (i = 1; i <= 7; i++) {
                    if (!current[i]) {
                        if (origin[i] && origin[i].length) {
                            res[i] = origin[i].slice(0)
                        }
                        continue
                    }
                    if (origin[i] && origin[i].length) {
                        m = selectMode === this.selectModes.JOIN
                            ? this.mergeArray(origin[i], current[i])
                            : this.rejectArray(origin[i], current[i])
                        m.length && (res[i] = m)
                    } else if (selectMode === this.selectModes.JOIN) {
                        res[i] = current[i].slice(0)
                    }
                }
                return res
            },

            getRangeSelectMode(startCoord, endCoord) {
                if (!this.multiple) {
                    return this.selectModes.REPLACE
                }
                let rowRange = this.sortCoord(startCoord[0], endCoord[0])
                let colRange = this.sortCoord(startCoord[1], endCoord[1])
                let startRow = rowRange[0]
                let endRow = rowRange[1]
                let startCol = colRange[0]
                let endCol = colRange[1]
                let rows = endRow - startRow + 1
                let cols = endCol - startCol + 1
                let total = rows * cols

                let used = 0, day = 0, data = [];
                for (let i = 0; i < rows; i++) {
                    day = startRow + i
                    data = this.selected[day]
                    if (!data) {
                        continue
                    }
                    for (let j = 0; j < data.length; j++) {
                        if (data[j] >= startCol && data[j] <= endCol) {
                            used++
                        }
                    }
                }

                return total === used ? this.selectModes.MINUS : this.selectModes.JOIN
            },

            getCellSelectMode(coord) {
                if (!this.multiple) {
                    return this.selectModes.REPLACE
                }
                let day = this.selected[coord[0]]
                return day && ~day.indexOf(coord[1]) ? this.selectModes.MINUS : this.selectModes.JOIN
            },

            updateToggle(startCoord, endCoord, selectMode) {
                this.updateRange(startCoord, endCoord, selectMode)
                this.end()
            },

            updateRange(startCoord, endCoord, selectMode) {
                let currentSelectRange = this.makeMatrix(startCoord, endCoord);
                this.tempSelected = this.merge(this.selected, currentSelectRange, selectMode);
            },

            end() {
                this.moving = false
                this.startCoord = null
                this.endCoord = null
                this.selectMode = this.selectModes.NONE
                this.emitChange(this.tempSelected)
            },

            reset() {
                if (this.disabled) {
                    return
                }
                this.emitChange({})
            },

            emitChange(val) {
                val = this.getWeekTime(val);
                this.$emit('input', val);
                this.$emit('change', val);
            },
            setWeekTime(data) {
                let ret = [], row1, row2, temp = [];
                if (data.length == 0) return [];
                data.forEach(function (item, index) {
                    temp = [];
                    item.forEach(function (row) {
                        row1 = row[0].split(':');
                        row2 = row[1].split(':');
                        for (let i = parseInt(row1[0]); i < parseInt(row2[0]); i++) {
                            temp.push(i)
                        }
                    });
                    if (temp.length > 0) {
                        // ret[index+1] = [];
                        ret[index + 1] = temp;
                    }
                });
                return ret;
            },
            getWeekTime(data) {
                let ret = [], temp, start = 0, end = 0, lastRow = 0, item = [];
                if (data.length == 0) return [];
                for (let i = 0; i < 7; i++) {
                    temp = [];
                    if (!data[i + 1]) {
                        ret.push([]);
                        continue;
                    }
                    item = data[i + 1];
                    item.forEach(function (row, index) {
                        if (index == 0 || start == 0) {
                            start = start ? start : row < 10 ? '0' + row + ':00' : row + ':00';
                        }
                        if (item.length == 1
                            || index == (item.length - 1)
                            || (item[index + 1] - row) > 1
                        ) {
                            end = row + 1;
                            end = end < 10 ? '0' + end + ':00' : end + ':00';
                            temp.push([start, end]);
                            start = 0;
                            end = 0;
                        }
                        lastRow = row;
                    });
                    ret.push(temp)
                }
                start = 0;
                ret.forEach(function (item) {
                    if(item.length ==0){
                        start++;
                    }
                });
                return start == 7 ? [] : ret;
            }
        },
    }
</script>
