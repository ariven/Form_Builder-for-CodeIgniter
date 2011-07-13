/**
 * swaps two array elements
 * 
 * @author patrick (5/25/2011)
 * 
 * @param source_array 
 * @param swap_first index of first element to swap
 * @param swap_second index of second element to swap 
 * @return returns modified array 
 */
function array_swap_elements(source_array, swap_first, swap_second) {
	var tem = source_array[swap_first];
	source_array[swap_first] = source_array[swap_second];
	source_array[swap_second] = tem;
	return source_array;
} // swap_elements

/**
 * returns a string formatted in a manner that PHP can 
 * unserialize it 
 * 
 * @author patrick (5/25/2011)
 * 
 * @param a 
 */
function x_array_serialize(source_array)
{
	var serializedString = '';
	var arrayLength = 0;
	for(var aKey in source_array)
	{
		//key definition
		if(aKey * 1 == aKey) //is_numeric?
		{
			//integer keys look like i:key
			serializedString += 'i:' + aKey + ';';	
		}
		else
		{
			//string keys look like s:key_length:key;
			serializedString += 's:' + aKey.length + ':"' + aKey + '";';
		}
		
		//value definition
		if(source_array[aKey] * 1 == source_array[aKey])
		{
			//integer value look like i:value
			serializedString += 'i:' + source_array[aKey] + ';';	
		}
		else if(typeof(source_array[aKey]) == "string")
		{
			//string value look like s:key_length:value;
			serializedString += 's:' + source_array[aKey].length + ':"' + source_array[aKey] + '";';
		}
		else if(source_array[aKey] instanceof Array)
		{
			serializedString += serializeArray(source_array[aKey]);
		}
		arrayLength++;
	}
	serializedString = 'source_array:' + arrayLength + ':{' + serializedString + '}';
	
	return serializedString;
}


function array_serialize (mixed_value) {
	// from http://phpjs.org/functions/serialize
	// 
	// 
	// 
    // Returns a string representation of variable (which can later be unserialized)  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/serialize    // +   original by: Arpad Ray (mailto:arpad@php.net)
    // +   improved by: Dino
    // +   bugfixed by: Andrej Pavlovic
    // +   bugfixed by: Garagoth
    // +      input by: DtTvB (http://dt.in.th/2008-09-16.string-length-in-bytes.html)    // +   bugfixed by: Russell Walker (http://www.nbill.co.uk/)
    // +   bugfixed by: Jamie Beck (http://www.terabit.ca/)
    // +      input by: Martin (http://www.erlenwiese.de/)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
    // +   improved by: Le Torbi (http://www.letorbi.de/)    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net/)
    // +   bugfixed by: Ben (http://benblume.co.uk/)
    // -    depends on: utf8_encode
    // %          note: We feel the main purpose of this function should be to ease the transport of data between php & js
    // %          note: Aiming for PHP-compatibility, we have to translate objects to arrays    // *     example 1: serialize(['Kevin', 'van', 'Zonneveld']);
    // *     returns 1: 'a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}'
    // *     example 2: serialize({firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'});
    // *     returns 2: 'a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}'
    var _utf8Size = function (str) {
        var size = 0,
            i = 0,
            l = str.length,
            code = '';
            for (i = 0; i < l; i++) {
            code = str.charCodeAt(i);
            if (code < 0x0080) {
                size += 1;
            } else if (code < 0x0800) {
                size += 2;            
            } else {
                size += 3;
            }
        }
        return size;
    };
    var _getType = function (inp) {
        var type = typeof inp,
            match;
        var key; 
        if (type === 'object' && !inp) {
            return 'null';
        }
        if (type === "object") {
            if (!inp.constructor) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }        }
        return type;
    };
    var type = _getType(mixed_value);
    var val, ktype = ''; 
    switch (type) {
    case "function":
        val = "";
        break;    case "boolean":
        val = "b:" + (mixed_value ? "1" : "0");
        break;
    case "number":
        val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
        break;
    case "string":
        val = "s:" + _utf8Size(mixed_value) + ":\"" + mixed_value + "\"";
        break;
    case "array":    case "object":
        val = "a";
/*
            if (type == "object") {
                var objname = mixed_value.constructor.toString().match(/(\w+)\(\)/);
                if (objname == undefined) {
                    return;
                }
                objname[1] = this.array_serialize(objname[1]);
                val = "O" + objname[1].substring(1, objname[1].length - 1);            }
            */
        var count = 0;
        var vals = "";
        var okey;
        var key;
        for (key in mixed_value) {
            if (mixed_value.hasOwnProperty(key)) {
                ktype = _getType(mixed_value[key]);
                if (ktype === "function") {
                    continue;
                }
 
                okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                vals += this.array_serialize(okey) + this.array_serialize(mixed_value[key]);
                count++;
            }
        }
        val += ":" + count + ":{" + vals + "}";
        break;    case "undefined":
        // Fall-through
    default:
        // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
        val = "N";        break;
    }
    if (type !== "object" && type !== "array") {
        val += ";";
    }    return val;
}
